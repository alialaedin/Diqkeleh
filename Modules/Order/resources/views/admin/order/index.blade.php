<x-layouts.master title="سفارشات">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="سفارشات" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت سفارش جدید" :route="route('admin.orders.create')" />
	</div>

	<x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.orders.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="شناسه سفارش" />
						<x-input type="number" name="order_id" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="نام مشتری" />
						<x-input type="text" name="customer_name" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="موبایل مشتری" />
						<x-input type="text" name="customer_mobile" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب وضعیت" />
						<x-select name="status" :data="$statuses" option-value="name" option-label="label" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="از تاریخ" />
						<x-date-input id="start_date" name="start_date" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="تا تاریخ" />
						<x-date-input id="end_date" name="end_date" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	<x-card title="سفارشات">
		<x-table :pagination="$orders">
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
						<td>{{ $order->customer->full_name . ' - ' . $order->customer->mobile }}</td>
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
							<button @class(['send-btn', 'btn', 'btn-sm', 'btn-icon', 'btn-success' => $order->isNew(), 'btn-dark' => !$order->isNew()]) data-toggle="tooltip" data-original-title="ارسال"
								data-id="{{ $order->id }}" @disabled(!$order->isNew())>
								<i class="fa fa-send"></i>
							</button>
							<button @class(['cancel-btn', 'btn', 'btn-sm', 'btn-icon', 'btn-danger' => $order->isNew(), 'btn-dark' => !$order->isNew()]) data-toggle="tooltip" data-original-title="کنسل کردن سفارش"
								data-id="{{ $order->id }}" @disabled(!$order->isNew())>
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

	@foreach ($orders as $order)
		<x-form id="send-sms-form-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
			:has-default-buttons="false" method="PATCH" class="d-none">
			<input type="hidden" name="status" value="delivered" />
		</x-form>
		<x-form id="cancel-order-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
			:has-default-buttons="false" method="PATCH" class="d-none">
			<input type="hidden" name="status" value="canceled" />
		</x-form>
	@endforeach

	@push('scripts')

		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')

		<script>
			$(document).ready(() => {
				$('.send-btn').each(function () {
					$(this).click(() => {
						$('#send-sms-form-' + $(this).data('id')).submit();
					});
				});
				$('.cancel-btn').each(function () {
					$(this).click(() => {
						$('#cancel-order-' + $(this).data('id')).submit();
					});
				});
			});	
		</script>

	@endpush

</x-layouts.master>