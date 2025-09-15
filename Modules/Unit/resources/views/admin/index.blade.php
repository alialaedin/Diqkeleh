<x-layouts.master title="واحد ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="واحد ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت واحد جدید" :route="route('admin.units.create')" />
	</div>
	
	<x-card title="واحد ها">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان انگیلیسی</th>
					<th>نام فارسی</th>
					<th>تعداد محصولات</th>
					<th>وضعیت</th>
					<th>تاریخ</th>
					<th>ساعت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($units as $unit)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $unit->name }}</td>
						<td>{{ $unit->label }}</td>
						<td>{{ $unit->products_count }}</td>
						<td>
							<x-badge 
								:type="$unit->status ? 'success' : 'danger'"
								:text="$unit->status ? 'فعال' : 'غیر فعال'"
							/>
						</td>
						<td><x-jalali-date :date="$unit->created_at" format="date" /></td>
						<td><x-jalali-date :date="$unit->created_at" format="time" /></td>
						<td>
							<x-edit-button :model="$unit" route="admin.units.edit" />
							<x-delete-button :model="$unit" route="admin.units.destroy" :disabled="$unit->products_count" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="8" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>