<?php

namespace Modules\Order\Services;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Order\Http\Requests\Admin\Order\OrderStoreRequest;
use Modules\Payment\Enums\PaymentType as EnumsPaymentType;
use Modules\Sms\Sms;
use Modules\Store\Enums\StoreType;
use Modules\Store\Services\BalanceChangerService;

class OrderCreatorService
{
	private Order $order;

	public function __construct(private readonly OrderStoreRequest $request) {}

	public function store(): void
	{
		DB::transaction(function () {
			$this->updateCustomer();
			$this->createOrder();
			$this->createOrderItems();
			$this->createPayments();
			$this->sendSms();
		});

		(new ActivityLogHelper($this->order))->created();
	}

	private function updateCustomer()
	{
		$customer = Customer::query()->findOrFail($this->request->input('customer_id'));
		$customer->update($this->request->only(['first_name', 'last_name']));
	}

	private function createOrder(): void
	{
		$this->order = Order::create([
			'customer_id' => $this->request->input('customer_id'),
			'address_id' => $this->request->input('address_id'),
			'courier_id' => $this->request->input('courier_id') ?? null,
			'address' => $this->getAddressAsJson(),
			'shipping_amount' => $this->request->input('shipping_amount'),
			'discount_amount' => $this->request->input('discount_amount'),
			'description' => $this->request->input('description') ?? null,
			'status' => OrderStatus::NEW,
		]);
	}

	private function createOrderItems(): void
	{
		$products = $this->request->input('products', []);

		foreach ($products as $product) {

			$item = $this->order->items()->create([
				'product_id' => $product['id'],
				'quantity' => $product['quantity'],
				'amount' => $product['amount'],
				'discount_amount' => $product['discount_amount'],
				'status' => 1,
			]);

			$data = (object) [
				'product_id' => $item->product_id,
				'quantity' => $item->quantity,
				'type' => StoreType::DECREMENT,
				'description' => "کم شدن در سفارش به شناسه {$this->order->id}",
			];

			(new BalanceChangerService($data))->changeBalance();
		}

		$this->order->refresh();
	}

	private function createPayments(): void
	{
		$paymentTypesKeys = ['cash_amount', 'card_by_card_amount', 'pos_amount'];
		$data = [];

		foreach ($paymentTypesKeys as $key) {
			$amount = $this->request->input($key);
			if ($amount > 0) {
				$data[] = [
					'customer_id' => $this->order->customer_id,
					'amount' => $amount,
					'type' => EnumsPaymentType::getTypeByRequestKey($key),
					'paid_at' => now(),
					'created_at' => now(),
					'updated_at' => now(),
				];
			}
		}

		if (!empty($data)) {
			DB::table('payments')->insert($data);
		}
	}

	private function sendSms()
	{
		if (app()->isProduction()) {
			$pattern = config('sms-patterns.new_order');
			Sms::pattern($pattern)
				->data([
					'token' => $this->order->id,
					'token1' => $this->order->customer->full_name,
				])
				->to([$this->order->customer->mobile])
				->send();
		}
	}

	private function getAddressAsJson()
	{
		return Address::query()
			->where('id', $this->request->input('address_id'))
			->first()
			->toJson();
	}
}
