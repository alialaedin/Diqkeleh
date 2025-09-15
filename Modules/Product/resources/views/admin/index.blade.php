<x-layouts.master title="محصولات">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="محصولات" />
		</x-breadcrumb>
		<x-link-create-button title="ثبت محصول جدید" :route="route('admin.products.create')" />
	</div>

	<x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.products.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="شناسه" />
						<x-input type="number" name="product_id" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="عنوان" />
						<x-input type="text" name="title" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب دسته بندی" />
						<x-select name="category_id" :data="$categories" option-value="id" option-label="title" />
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

	<x-card title="محصولات">
		<x-table :pagination="$products">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان</th>
					<th>دسته بندی</th>
					<th>قیمت پایه (تومان)</th>
					<th>تخفیف (تومان)</th>
					<th>قیمت نهایی (تومان)</th>
					<th>وضعیت</th>
					<th>تاریخ ثبت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($products as $product)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $product->title }}</td>
						<td>{{ $product->category->title }}</td>
						<td>{{ number_format($product->unit_price) }}</td>
						<td>{{ number_format($product->discount_amount) }}</td>
						<td>{{ number_format($product->final_price) }}</td>
						<td>
							<x-badge :type="$product->status->color()" :text="$product->status->label()" />
						</td>
						<td><x-jalali-date :date="$product->created_at" /></td>
						<td>
							<x-show-button :model="$product" route="admin.products.show" />
							<x-edit-button :model="$product" route="admin.products.edit" />
							<x-delete-button :model="$product" route="admin.products.destroy" :disabled="$product->isDeletable()" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="10" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@push('scripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>