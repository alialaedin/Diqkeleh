<?php

namespace Modules\Payment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Payment\Enums\PaymentType;
use Modules\Payment\Http\Requests\Admin\PaymentStoreRequest;
use Modules\Payment\Http\Requests\Admin\PaymentUpdateRequest;
use Modules\Payment\Models\Payment;

class PaymentController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_payments', ['index']),
			new Middleware('permission:create_payments', ['create', 'store']),
			new Middleware('permission:update_payments', ['edit', 'update']),
			new Middleware('permission:delete_payments', ['destroy']),
		];
	}

	public function index()
	{
		$types = PaymentType::getCasesWithLabel();
		$payments = Payment::query()->with('customer:id,full_name,mobile')->latest()->filters()->paginateOrAll();

		return view('payment::admin.index', compact(['payments', 'types']));
	}

	public function create()
	{
		$types = PaymentType::getCasesWithLabel();

		return view('payment::admin.create', compact('types'));
	}

	public function store(PaymentStoreRequest $request)
	{
		$payment = Payment::create($request->validated());
		$dec = "پرداختی به میزان " . number_format($payment->amount) . " تومان برای {$payment->customer->full_name} ثبت شد";

		(new ActivityLogHelper($payment, $dec))->created();

		return to_route('admin.payments.index')->with('status', 'پرداختی با موفقیت ثبت شد');
	}

	public function edit(Payment $payment)
	{
		$types = PaymentType::getCasesWithLabel();

		return view('payment::admin.edit', compact(['types', 'payment']));
	}

	public function update(PaymentUpdateRequest $request, Payment $payment)
	{
		$payment->update($request->validated());
		(new ActivityLogHelper($payment))->updated();

		return to_route('admin.payments.index')->with('status', 'پرداختی با موفقیت بروزرسانی شد');
	}

	public function destroy(Payment $payment)
	{
		$payment->delete();
		(new ActivityLogHelper($payment))->deleted();

		return to_route('admin.payments.index')->with('status', 'پرداختی با موفقیت حذف شد');
	}
}
