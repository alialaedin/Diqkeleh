<x-layouts.master title="نقش ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="نقش ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت نقش جدید" :route="route('admin.roles.create')" />
	</div>

	<x-card title="نقش ها">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان انگیلیسی</th>
					<th>نام فارسی</th>
					<th>تاریخ ثبت</th>
					<th>تاریخ آخرین ویرایش</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($roles as $role)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $role->name }}</td>
						<td>{{ $role->label }}</td>
						<td><x-jalali-date :date="$role->created_at" /></td>
						<td><x-jalali-date :date="$role->updated_at" /></td>
						<td>
              <x-show-button :model="$role" route="admin.roles.show" />
              <x-edit-button :model="$role" route="admin.roles.edit" />
							<x-delete-button :model="$role" route="admin.roles.destroy" :disabled="!$role->isDeletable()" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="6" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>