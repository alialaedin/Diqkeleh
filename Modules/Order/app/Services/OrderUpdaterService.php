<?php

namespace Modules\Order\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Core\Exceptions\ValidationException;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Setting\Models\Setting;
use Modules\Sms\Sms;
use Modules\Store\Enums\StoreType;
use Modules\Store\Services\BalanceChangerService;

class OrderUpdaterService
{
	public function __construct(
		private readonly Order $order,
		private readonly Request $request
	) {
		$this->assertOrderEditable();
	}

	private function assertOrderEditable()
	{
		if (in_array($this->order->status, [OrderStatus::CANCELED, OrderStatus::DELIVERED], true)) {
			throw new ValidationException('سفارش ها با وضعیت کنسل و ارسال شده قابل ویرایش نمی باشند');
		}
	}

	public function changeStatus(): void
	{
		$newStatus = $this->request->input('status');

		if ($newStatus == $this->order->status->value) {
			return;
		}

		$updateData = ['status' => $newStatus];

		if ($newStatus == OrderStatus::DELIVERED->value) {
			$updateData['delivered_at'] = now();
			$updateData['courier_id'] = $this->request->input('courier_id') ?? null;
		}

		$this->order->update($updateData);

		$message = "وضعیت سفارش با شناسه {$this->order->id} به {$this->order->status->label()} تغییر پیدا کرد";
		$this->logActivity($this->order, $message, 'updated');

		if ($newStatus == OrderStatus::CANCELED->value) {
			$this->cancelActiveItems();
		} else if ($newStatus == OrderStatus::DELIVERED->value) {
			$this->sendSms(OrderStatus::DELIVERED);
		}
	}

	private function cancelActiveItems(): void
	{
		$this->order->activeItems()->each(function (OrderItem $item) {
			$this->changeBalanceForItem(
				$item->product_id,
				$item->quantity,
				StoreType::INCREMENT,
				"به دلیل کنسل شدن سفارش به شناسه {$this->order->id}"
			);
		});
		$this->sendSms(OrderStatus::CANCELED);
	}

	private function sendSms(OrderStatus $newStatus)
	{
		$status = $newStatus->value;
		
		$smsSettings = [
			OrderStatus::CANCELED->value => ['key' => 'order_canceled', 'setting' => 'send_canceled_order_sms'],
			OrderStatus::DELIVERED->value => ['key' => 'order_delivered', 'setting' => 'send_delivered_order_sms'],
		];

		if (!isset($smsSettings[$status])) {
			return;
		}

		$key = $smsSettings[$status]['key'];
		$sendSms = Setting::getFromName($smsSettings[$status]['setting']);

		if (app()->isProduction() && $sendSms && !empty($this->order?->customer?->mobile)) {
			$pattern = config('sms-patterns.' . $key);
			Sms::pattern($pattern)
				->data(['token' => $this->order->id])
				->to([$this->order->customer->mobile])
				->send();
		}
	}

	public function addItem(): void
	{
		$product = $this->request->input('product');
		$quantity = (int) $this->request->input('quantity');

		$orderItem = $this->order->items()->create([
			'product_id' => $product->id,
			'quantity' => $quantity,
			'amount' => $product->unit_price,
			'discount_amount' => $product->discount_amount,
			'status' => 1,
		]);

		$this->changeBalanceForItem(
			$orderItem->product_id,
			$orderItem->quantity,
			StoreType::DECREMENT,
			"کم شدن موجودی در سفارش به شناسه {$this->order->id}"
		);

		$message = "محصول {$orderItem->product->title} به سفارش با شناسه {$this->order->id} اضافه شد";
		$this->logActivity($orderItem, $message, 'created');
	}

	public function updateQuantity(): void
	{
		$orderItem = $this->request->route('orderItem');

		$newQuantity = (int) $this->request->input('quantity');
		$oldQuantity = $orderItem->getOriginal('quantity');

		if ($newQuantity === $oldQuantity) {
			return; // no change
		}

		$diffQuantity = $newQuantity - $oldQuantity;
		$absDiffQuantity = abs($diffQuantity);

		$orderItem->quantity = $newQuantity;
		$orderItem->save();

		$balanceType = $diffQuantity > 0 ? StoreType::DECREMENT : StoreType::INCREMENT;
		$description = $diffQuantity > 0
			? "کم شدن موجودی در سفارش به شناسه {$this->order->id}"
			: "افزایش موجودی در سفارش به شناسه {$this->order->id}";

		$this->changeBalanceForItem($orderItem->product_id, $absDiffQuantity, $balanceType, $description);

		$action = $diffQuantity > 0 ? 'اضافه شد' : 'کم شد';
		$message = "به محصول {$orderItem->product->title} در سفارش به شناسه {$this->order->id} {$absDiffQuantity} عدد {$action}";
		$this->logActivity($orderItem, $message, 'updated');
	}

	public function changeItemStatus(): void
	{
		$orderItem = $this->request->route('orderItem');
		$newStatus = ! (bool) $orderItem->status;

		if ($orderItem->status == $newStatus) {
			return; // no change
		}

		$orderItem->status = $newStatus;
		$orderItem->save();

		$balanceType = $newStatus ? StoreType::DECREMENT : StoreType::INCREMENT;
		$description = $newStatus
			? "به دلیل فعال شدن محصول {$orderItem->product->title} در سفارش به شناسه {$this->order->id}"
			: "به دلیل غیر فعال شدن محصول {$orderItem->product->title} در سفارش به شناسه {$this->order->id}";

		$this->changeBalanceForItem($orderItem->product_id, $orderItem->quantity, $balanceType, $description);

		$state = $newStatus ? 'فعال شد' : 'غیر فعال شد';
		$message = "وضعیت محصول {$orderItem->product->title} در سفارش به شناسه {$this->order->id} {$state}";
		$this->logActivity($orderItem, $message, 'updated');
	}

	private function changeBalanceForItem(int $productId, int $quantity, StoreType $type, string $description): void
	{
		$data = (object) [
			'product_id' => $productId,
			'quantity' => $quantity,
			'type' => $type,
			'description' => $description,
		];

		(new BalanceChangerService($data))->changeBalance();
	}

	private function logActivity(Model $model, string $message, string $method = 'updated'): void
	{
		$activity = new ActivityLogHelper($model, $message);
		$activity->{$method}();
	}
}
