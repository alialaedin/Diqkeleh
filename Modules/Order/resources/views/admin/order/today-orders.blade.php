<x-layouts.master title="سفارشات">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="سفارشات" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت سفارش جدید" :route="route('admin.orders.create')" />
	</div>

	<x-card title="سفارشات امروز">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه سفارش</th>
					<th>مشتری</th>
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
				@forelse ($orders as $order)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>
							<x-badge :text="$order->id" type="dark" />
						</td>
						<td>
							<a href="{{ route('admin.customers.show', $order->customer) }}">
								{{ $order->customer->full_name . ' - ' . $order->customer->mobile }}
							</a>
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
							<button class="send-btn btn btn-sm btn-icon btn-lime" data-toggle="tooltip" data-original-title="ارسال"
								data-id="{{ $order->id }}" @disabled(!$order->isNew())>
								<i class="fa fa-send"></i>
							</button>
							<button class="btn btn-sm btn-yellow btn-icon pay-btn" data-toggle="tooltip" data-original-title="پرداخت"
								data-id="{{ $order->id }}" @disabled($order->isCanceled())>
								<i class="fa fa-money"></i>
							</button>
							<button class="cancel-btn btn btn-sm btn-icon btn-red" data-toggle="tooltip"
								data-original-title="کنسل کردن سفارش" data-id="{{ $order->id }}" @disabled(!$order->isNew())>
								<i class="fa fa-times-circle-o"></i>
							</button>
							<x-print-button :model="$order" route="admin.orders.print" />
							<x-show-button :model="$order" route="admin.orders.show" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="11" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@foreach ($orders->where('status', Modules\Order\Enums\OrderStatus::NEW) as $order)

		<x-modal id="send-sms-modal-{{ $order->id }}" title="ارسال غذا" size="md">
			<x-form action="{{ route('admin.orders.change-status', $order) }}" :has-default-buttons="false" method="PATCH">

				<input type="hidden" name="status" value="delivered" />

				<fieldset class="form-group">
					<x-label text="انتخاب پیک" />
					<x-select name="courier_id" :data="$couriers" option-value="id" option-label="full_name" />
				</fieldset>

				<x-row>
					<x-col>
						<button type="submit" class="btn btn-success btn-block">ارسال</button>
						<button type="button" class="btn btn-danger btn-block" data-dismiss="modal">بستن</button>
					</x-col>
				</x-row>

			</x-form>
		</x-modal>

		<x-form id="cancel-order-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
			:has-default-buttons="false" method="PATCH" class="d-none">
			<input type="hidden" name="status" value="canceled" />
		</x-form>

	@endforeach

	@foreach ($orders->where('status', '!=', Modules\Order\Enums\OrderStatus::CANCELED) as $order)
		<x-modal id="pay-modal-{{ $order->id }}" title="پرداخت" size="lg">
			<x-form action="{{ route('admin.orders.pay') }}" method="POST">
				<x-row>

					<input hidden name="customer_id" value="{{ $order->customer_id }}">

					<x-col xl="6">
						<x-form-group>
							<x-label text="مشتری" />
							<x-input type="text" id="customer{{ $order->id }}" name="" :default-value="$order->customer->full_name" readonly />
						</x-form-group>
					</x-col>

					<x-col xl="6">
						<x-form-group>
							<x-label :is-required="true" text="انتخاب نوع پرداخت" />
							<x-select name="type" id="type{{ $order->id }}" :data="$types" option-value="name" option-label="label" />
						</x-form-group>
					</x-col>

					<x-col xl="6">
						<x-form-group>
							<x-label :is-required="true" text="میزان پرداختی (تومان)" />
							<x-input type="text" id="amount{{ $order->id }}" class="comma" name="amount"
								:default-value="number_format($order->total_amount)" />
						</x-form-group>
					</x-col>

					<x-col xl="6">
						<x-form-group>
							<x-label text="زمان پرداخت" />
							<x-date-input id="paid_at_{{ $order->id }}" name="paid_at" />
						</x-form-group>
					</x-col>

					<x-col>
						<x-form-group>
							<x-label text="توضیحات" />
							<x-textarea id="description{{ $order->id }}" name="description" rows="4" />
						</x-form-group>
					</x-col>
				</x-row>
			</x-form>
		</x-modal>
	@endforeach

	@push('scripts')

		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')

		<script>
			$(document).ready(() => {
				$('.send-btn').each(function () {
					$(this).click(() => {
						$('#send-sms-modal-' + $(this).data('id')).modal('show');
					});
				});
				$('.pay-btn').each(function () {
					$(this).click(() => {
						$('#pay-modal-' + $(this).data('id')).modal('show');
					});
				});
				$('.cancel-btn').each(function () {
					$(this).click(() => {
						Swal.fire({
							title: "آیا مطمئن هستید؟",
							text: "بعد از کنسل کردن سفارش دیگر نمی توانید آن را ویرایش کنید!",
							icon: "warning",
							showCancelButton: true,
							showCloseButton: true,
							confirmButtonColor: "#d33",
							cancelButtonColor: "#3085d6",
							confirmButtonText: "کنسل کردن سفارش",
							cancelButtonText: "انصراف",
							dangerMode: true,
							customClass: {
								popup: 'sweet-alert-size'
							}
						}).then((result) => {
							if (result.isConfirmed) {
								$('#cancel-order-' + $(this).data('id')).submit();
							}
						});
					});
				});
			});	
		</script>

	@endpush

</x-layouts.master>