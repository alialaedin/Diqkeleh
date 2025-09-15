<x-layouts.master title="دسته بندی ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="دسته بندی ها" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت دسته بندی جدید" :route="route('admin.categories.create')" />
	</div>
	
	<x-card title="دسته بندی ها">
		<x-table>
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان فارسی</th>
					<th>عنوان انگیلیسی</th>
					<th>تعداد محصولات</th>
					<th>وضعیت</th>
					<th>تاریخ</th>
					<th>ساعت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($categories as $category)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $category->title }}</td>
						<td>{{ $category->en_title }}</td>
						<td>{{ $category->products_count }}</td>
						<td>
							<x-badge 
								:type="$category->status ? 'success' : 'danger'"
								:text="$category->status ? 'فعال' : 'غیر فعال'"
							/>
						</td>
						<td><x-jalali-date :date="$category->created_at" format="date" /></td>
						<td><x-jalali-date :date="$category->created_at" format="time" /></td>
						<td>
							<x-edit-button :model="$category" route="admin.categories.edit" />
							<x-delete-button :model="$category" route="admin.categories.destroy" :disabled="$category->products_count" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="7" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>