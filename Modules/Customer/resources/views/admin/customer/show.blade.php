<x-layouts.master title="مشتریان">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="مشتریان" :route="route('admin.customers.index')" />
			<x-breadcrumb-item title="جزئیات مشتری" />
		</x-breadcrumb>
		<div>
			<x-edit-button :model="$customer" route="admin.customers.edit" title="ویرایش" />
			<x-delete-button :model="$customer" route="admin.customers.destroy" title="حذف" />
		</div>
	</div>

	<x-card title="اطلاعات مشتری">
		<x-row>
			<x-col lg="6" xl="6">
				<ul class="list-group">
					<li class="list-group-item p-3 fs-14"><b class="fs-13">شناسه :</b> {{ $customer->id }}</li>
					<li class="list-group-item p-3 fs-14"><b class="fs-13">نام کامل :</b> {{ $customer->full_name }}</li>
					<li class="list-group-item p-3 fs-14"><b class="fs-13">شماره همراه :</b> {{ $customer->mobile }}</li>
				</ul>
			</x-col>
			<x-col lg="6" xl="6">
				<ul class="list-group">
					<li class="list-group-item p-3 fs-14"><b class="fs-13">تاریخ ثبت :</b> {{ verta($customer->created_at) }}</li>
					<li class="list-group-item p-3 fs-14"><b class="fs-13">تعداد سفارشات :</b> {{ $customer->orders_count }}</li>
					<li class="list-group-item p-3 fs-14"><b class="fs-13">تعداد پرداختی ها :</b> {{ $customer->payments_count }}
					</li>
				</ul>
			</x-col>
		</x-row>
	</x-card>

	@php
		$walletData = [
			['title' => 'موجودی کیف پول (تومان)', 'value' => number_format($customer->wallet->balance), 'color' => 'primary', 'icon' => 'dollar'],
			['title' => 'تعداد شارژ', 'value' => $customer->deposits_count, 'color' => 'success', 'icon' => 'money'],
			['title' => 'تعداد برداشت', 'value' => $customer->withdraws_count, 'color' => 'danger', 'icon' => 'credit-card'],
		];
	@endphp

	<x-row>
		@foreach ($walletData as $d)
			<x-col lg="4" xl="4">
				<x-info-box-2 :title="$d['title']" :value="$d['value']" :color="$d['color']" :icon="$d['icon']" />
			</x-col>
		@endforeach
	</x-row>

	<x-card title="تراکنش های کیف پول">
		<x-slot name="options">
			<div class="d-flex" style="gap: 5px;">
				<button data-target="#deposit" data-toggle="modal" class="btn btn-success btn-sm">افزایش موجودی</button>
				<button data-target="#withdraw" data-toggle="modal" class="btn btn-danger btn-sm">کاهش موجودی</button>
				<a class="btn btn-sm btn-primary" target="_blank"
					href="{{ route('admin.wallet-transactions.index', ['customer_mobile' => $customer->mobile]) }}">
					مشاهده همه
				</a>
			</div>
		</x-slot>
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه تراکنش</th>
					<th>مبلغ (تومان)</th>
					<th>نوع تراکنش</th>
					<th>توضیحات</th>
					<th>تاریخ تراکنش</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($customer->walletTransactions as $transaction)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $transaction->id }}</td>
						<td>{{ number_format($transaction->amount) }}</td>
						<td>
							<x-badge :type="$transaction->type->color()" :text="$transaction->type->label()" :is-light="true" />
						</td>
						<td style="text-wrap: wrap">{{ $transaction->description }}</td>
						<td><x-jalali-date :date="$transaction->created_at" /></td>
					</tr>
				@empty
					<x-no-data :colspan="6" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	<x-card title="لیست آدرس ها">
		<x-slot name="options">
			<div class="d-flex" style="gap: 5px;">
				<a class="btn btn-sm btn-indigo" target="_blank"
					href="{{ route('admin.addresses.create', ['customer_id' => $customer->id]) }}">
					آدرس جدید
				</a>
				<a class="btn btn-sm btn-primary" target="_blank"
					href="{{ route('admin.addresses.index', ['mobile' => $customer->mobile]) }}">
					مشاهده همه
				</a>
			</div>
		</x-slot>
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه</th>
					<th>نام گیرنده</th>
					<th>نام خانوادگی گیرنده</th>
					<th>شماره همراه</th>
					<th>تاریخ ثبت</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($customer->addresses as $address)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $address->id }}</td>
						<td>{{ $address->first_name }}</td>
						<td>{{ $address->last_name }}</td>
						<td>{{ $address->mobile }}</td>
						<td><x-jalali-date :date="$address->created_at" /></td>
					</tr>
				@empty
					<x-no-data :colspan="6" desc="آدرسی برای مشتری ثبت نشده است" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@php
		$data = [
			['title' => 'میزان خرید (تومان)', 'value' => number_format($customer->total_sales_amount), 'color' => 'primary', 'icon' => 'shopping-cart'],
			['title' => 'میزان پرداختی (تومان)', 'value' => number_format($customer->total_payment_amount), 'color' => 'success', 'icon' => 'money'],
			['title' => 'باقی مانده (تومان)', 'value' => number_format($customer->remaining_amount), 'color' => 'danger', 'icon' => 'bar-chart'],
		];
	@endphp

	<x-row>
		@foreach ($data as $d)
			<x-col lg="4" xl="4">
				<x-info-box :title="$d['title']" :value="$d['value']" :color="$d['color']" :icon="$d['icon']" />
			</x-col>
		@endforeach
	</x-row>

	<x-card title="فاکتور های خرید">
		<x-slot name="options">
			<a class="btn btn-sm btn-primary" target="_blank"
				href="{{ route('admin.orders.index', ['customer_mobile' => $customer->mobile]) }}">
				مشاهده همه
			</a>
		</x-slot>
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه سفارش</th>
					<th>جمع آیتم ها</th>
					<th>هزینه ارسال</th>
					<th>تخفیف</th>
					<th>مبلغ نهایی</th>
					<th>وضعیت</th>
					<th>تاریخ ارسال</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($customer->orders as $order)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>
							<x-badge :text="$order->id" type="dark" />
						</td>
						<td>{{ number_format($order->total_items_amount) }}</td>
						<td>{{ number_format($order->shipping_amount) }}</td>
						<td>{{ number_format($order->discount_amount) }}</td>
						<td>{{ number_format($order->total_amount) }}</td>
						<td>
							<x-badge :type="$order->status->color()" :text="$order->status->label()" />
						</td>
						<td>
							@if ($order->delivered_at)
								<x-jalali-date :date="$order->delivered_at" />
							@else
								<span>-</span>
							@endif
						</td>
						<td><x-jalali-date :date="$order->created_at" /></td>
						<td>
							<a href="{{route('admin.orders.show', $order)}}" target="_blank" class="btn btn-sm btn-cyan">
								جزئیات فاکتور
							</a>
						</td>
					</tr>
				@empty
					<x-no-data :colspan="10" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	<x-card title="پرداختی های">
		<x-slot name="options">
			<div class="d-flex" style="gap: 5px;">
				<a class="btn btn-sm btn-indigo" target="_blank"
					href="{{ route('admin.payments.create', ['customer_id' => $customer->id]) }}">
					پرداختی جدید
				</a>
				<a class="btn btn-sm btn-primary" target="_blank"
					href="{{ route('admin.payments.index', ['customer_mobile' => $customer->mobile]) }}">
					مشاهده همه
				</a>
			</div>
		</x-slot>
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>مبلغ (تومان)</th>
					<th>نوع پرداخت</th>
					<th>تاریخ ثبت</th>
					<th>تاریخ پرداخت</th>
					<th>توضیحات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($customer->payments as $payment)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ number_format($payment->amount) }}</td>
						<td>
							<x-badge :type="$payment->type->color()" :text="$payment->type->label()" />
						</td>
						<td><x-jalali-date :date="$payment->created_at" /></td>
						<td>
							@if ($payment->paid_at)
								<x-jalali-date :date="$payment->paid_at" />
							@else
								<span>-</span>
							@endif
						</td>
						<td>
							<button class="btn btn-sm btn-icon btn-dark text-white show-description-modal" data-id="{{ $payment->id }}">
								<i class="fa fa-folder-o"></i>
							</button>
						</td>
					</tr>
				@empty
					<x-no-data :colspan="6" desc="پرداختی ای یافت نشد !" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	<x-modal title="افزایش موجودی کیف پول" id="deposit">
		<x-form :action="route('admin.wallets.deposit')" method="POST">
			<x-row>

				<input hidden name="customer_id" value="{{ $customer->id }}">

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="مبلغ (تومان)" />
						<x-input type="text" name="amount" class="comma" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="توضیحات" />
						<x-textarea type="text" name="description" rows="3" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-checkbox name="deposit_gift_balance" title="افزایش موجودی هدیه" />
						<x-checkbox name="send_sms" title="ارسال پیام به مشتری" />
					</x-form-group>
				</x-col>

			</x-row>
		</x-form>
	</x-modal>

	<x-modal title="کاهش موجودی کیف پول" id="withdraw">
		<x-form :action="route('admin.wallets.withdraw')" method="POST">
			<x-row>

				<input hidden name="customer_id" value="{{ $customer->id }}">

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="مبلغ (تومان)" />
						<x-input type="text" name="amount" class="comma" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="توضیحات" />
						<x-textarea type="text" name="description" rows="3" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-checkbox name="withrdaw_gift_balance_too" title="از موجودی هدیه ام کم بشود" />
						<x-checkbox name="send_sms" title="ارسال پیام به مشتری" />
					</x-form-group>
				</x-col>

			</x-row>
		</x-form>
	</x-modal>

	@push('scripts')
		<script>
			$(document).ready(() => {
				const payments = @json($customer->payments);
				$('.show-description-modal').each(function () {
					$(this).on('click', function () {
						const paymentId = $(this).data('id');
						const payment = payments.find(m => m.id == paymentId);
						Swal.fire({
							title: 'توضیحات پرداختی',
							text: payment?.description || 'توضیحی ثبت نشده است',
							icon: 'info',
							confirmButtonText: "بستن",
						});
					});
				});
			});
		</script>
	@endpush

</x-layouts.master>