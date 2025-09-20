<x-layouts.master title="محدوده ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="محدوده ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت محدوده جدید" :route="route('admin.ranges.create')" />
	</div>

	<x-card title="محدوده ها">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه</th>
					<th>عنوان</th>
					<th>هزینه ارسال (تومان)</th>
					<th>وضعیت</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($ranges as $range)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $range->id }}</td>
						<td>{{ $range->title }}</td>
						<td>{{ number_format($range->shipping_amount) }}</td>
						<td>
							<x-badge :text="$range->status ? 'فعال' : 'غیر فعال'" :type="$range->status ? 'success' : 'danger'" />
						</td>
						<td><x-jalali-date :date="$range->created_at" /></td>
						<td>
							<x-edit-button :model="$range" route="admin.ranges.edit" />
							<x-delete-button :model="$range" route="admin.ranges.destroy" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="7" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>