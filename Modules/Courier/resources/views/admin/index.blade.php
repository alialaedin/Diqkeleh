<x-layouts.master title="پیک ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="پیک ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت پیک جدید" :route="route('admin.couriers.create')" />
	</div>
	
	<x-card title="پیک ها">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>نام</th>
					<th>نام خانوادگی</th>
					<th>شماره همراه</th>
					<th>کد ملی</th>
					<th>نوع پیک</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($couriers as $courier)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $courier->first_name }}</td>
						<td>{{ $courier->last_name }}</td>
						<td>{{ $courier->mobile }}</td>
						<td>{{ $courier->national_code }}</td>
						<td>
							<x-badge 
								:type="$courier->type->color()"
								:text="$courier->type->label()"
							/>
						</td>
						<td><x-jalali-date :date="$courier->created_at" /></td>
						<td>
							<x-edit-button :model="$courier" route="admin.couriers.edit" />
							<x-delete-button :model="$courier" route="admin.couriers.destroy" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="8" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>