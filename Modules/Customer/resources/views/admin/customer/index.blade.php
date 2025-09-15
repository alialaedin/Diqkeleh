<x-layouts.master title="مشتریان">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="مشتریان" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت مشتری جدید" :route="route('admin.customers.create')" />
	</div>

	<x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.customers.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="شناسه" />
						<x-input type="number" name="customer_id" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="نام" />
						<x-input type="text" name="first_name" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="نام خانوادگی" />
						<x-input type="text" name="last_name" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="موبایل" />
						<x-input type="text" name="mobile" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب وضعیت" />
						<x-select name="status" :data="$statuses" option-value="value" option-label="label" />
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

	<x-card title="مشتریان">
		<x-table :pagination="$customers">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه</th>
					<th>نام</th>
					<th>نام خانوادگی</th>
					<th>شماره همراه</th>
					<th>موجودی کیف پول (تومان)</th>
					<th>وضعیت</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($customers as $customer)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $customer->id }}</td>
						<td>{{ $customer->first_name }}</td>
						<td>{{ $customer->last_name }}</td>
						<td>{{ $customer->mobile }}</td>
						<td>{{ number_format($customer->wallet->balance) }}</td>
						<td>
							<x-badge :type="$customer->status->color()" :text="$customer->status->label()" />
						</td>
						<td><x-jalali-date :date="$customer->created_at" /></td>
						<td>

							<a class="btn btn-vk btn-sm btn-icon" target="_blank" data-toggle="tooltip" data-original-title="آدرس ها"
								href="{{ route('admin.addresses.index', ['mobile' => $customer->mobile]) }}">
								<i class="fa fa-map"></i>
							</a>

							<a class="btn btn-github btn-sm btn-icon" target="_blank" style="padding-inline: 10.5px" data-toggle="tooltip"
								data-original-title="تراکنش های کیف پول"
								href="{{ route('admin.wallet-transactions.index', ['customer_mobile' => $customer->mobile]) }}">
								<i class="fa fa-dollar"></i>
							</a>

							<a class="btn btn-green btn-icon btn-sm" target="_blank" data-toggle="tooltip"
								data-original-title="پرداختی ها"
								href="{{ route('admin.payments.index', ['customer_mobile' => $customer->mobile]) }}">
								<i class="fa fa-money"></i>
							</a>

							<x-show-button :model="$customer" route="admin.customers.show" />
							<x-edit-button :model="$customer" route="admin.customers.edit" />
							<x-delete-button :model="$customer" route="admin.customers.destroy" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="8" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@push('scripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>