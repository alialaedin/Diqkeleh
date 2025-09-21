<?php

namespace Modules\Courier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Courier\Http\Requests\Admin\SettlementRequest;
use Modules\Courier\Models\Courier;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentType;

class SettlementController extends Controller
{
	public function index()
	{
		if (!request()->has('start_date') || request()->isNotFilled('start_date')) {
			request()->merge([
				'start_date' => Carbon::parse(verta()->startDay()->toCarbon())->format('Y-m-d H:i')
			]);
		}

		if (!request()->has('end_date') || request()->isNotFilled('end_date')) {
			request()->merge([
				'end_date' => now()->format('Y-m-d H:i')
			]);
		}

		$orders = collect();
		$couriers = Courier::getAll();
		$requestCourierId = request()->courier_id;

		if ($requestCourierId) {
			$orders = Courier::query()->with('orders', function ($q) {
				$q->where('created_at', '>', Carbon::parse(request()->start_date)->subDay());
				$q->where('created_at', '<=', request()->end_date);
				$q->delivered();
				$q->isNotSettled();
				$q->with('customer:id,full_name,mobile');
			})->findOrFail($requestCourierId)->orders->each(function ($o) {
				$o->shamsi_created_at = verta($o->created_at)->format('H:i Y/m/d');
				$o->shamsi_delivered_at = verta($o->delivered_at)->format('H:i Y/m/d');
			});
		}

		return view('courier::admin.settlement', compact(['orders', 'couriers']));
	}

	public function update(SettlementRequest $request)
	{
		DB::transaction(function () use ($request) {

			$requestOrders = $request->input('orders');
			$orderIds = collect($requestOrders)->pluck('id')->toArray();
			$orders = Order::query()->whereIn('id', $orderIds)->get();
			$payTypes = ['cash_amount', 'card_by_card_amount', 'pos_amount'];
			$data = [];
			$now = now();

			foreach ($requestOrders as $reqOrder) {
				$order = $orders->firstWhere('id', $reqOrder['id']);

				if (!$order) {
					continue;
				}

				foreach ($payTypes as $payType) {
					if (isset($reqOrder[$payType]) && is_numeric($reqOrder[$payType]) && $reqOrder[$payType] > 0) {
						$data[] = [
							'customer_id' => $order->customer_id,
							'amount' => $reqOrder[$payType],
							'type' => PaymentType::getTypeByRequestKey($payType),
							'description' => "ثبت پرداختی سفارش به شناسه {$order->id} هنگام تسویه با پیک",
							'paid_at' => $now,
							'created_at' => $now,
							'updated_at' => $now,
						];
					}
				}
			}

			Order::whereIn('id', $orderIds)->update(['is_settled' => 1]);

			if (!empty($data)) {
				DB::table('payments')->insert($data);
			}
		});

		return response()->success('حساب پیک تسویه شد');
	}
}
