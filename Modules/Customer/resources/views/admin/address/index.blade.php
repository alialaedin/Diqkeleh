<x-layouts.master title="آدرس ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="آدرس ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت آدرس جدید" :route="route('admin.addresses.create')" />
	</div>

	<x-card title="آدرس ها">
		<x-table :pagination="$addresses">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه</th>
					<th>مشتری</th>
					<th>محدوده</th>
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
						<td>{{ $address->range->title }}</td>
						<td>{{ $address->mobile }}</td>
						<td><x-jalali-date :date="$address->created_at" /></td>
						<td>
							<x-edit-button :model="$address" route="admin.addresses.edit" />
							<x-delete-button :model="$address" route="admin.addresses.destroy" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="7" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@push('scripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>