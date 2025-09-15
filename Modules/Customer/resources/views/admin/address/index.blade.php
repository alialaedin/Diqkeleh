<x-layouts.master title="آدرس ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="آدرس ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت آدرس جدید" :route="route('admin.addresses.create')" />
	</div>

	<x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.addresses.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="شناسه مشتری" />
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

	<x-card title="آدرس ها">
		<x-table :pagination="$addresses">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه</th>
					<th>مشتری</th>
					<th>نام گیرنده</th>
					<th>نام خانوادگی گیرنده</th>
					<th>شماره همراه</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($addresses as $address)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $address->id }}</td>
						<td>{{ $address->customer->full_name }}</td>
						<td>{{ $address->first_name }}</td>
						<td>{{ $address->last_name }}</td>
						<td>{{ $address->mobile }}</td>
						<td><x-jalali-date :date="$address->created_at" /></td>
						<td>
							<x-edit-button :model="$address" route="admin.addresses.edit" />
							<x-delete-button :model="$address" route="admin.addresses.destroy" />
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