<x-layouts.master title="محصولات">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="محصولات" :route="route('admin.products.index')" />
			<x-breadcrumb-item title="ویرایش محصول" />
		</x-breadcrumb>
	</div>

	<x-card title="محصول جدید">
		<x-form :action="route('admin.products.update', $product)" method="PATCH">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان" />
						<x-input type="text" name="title" :default-value="$product->title" required autofocus />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب دسته بندی" />
						<x-select name="category_id" :data="$categories" option-value="id" option-label="title"
							:default-value="$product->category_id" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب وضعیت" />
						<x-select name="status" :data="$statuses" option-value="name" option-label="label"
							:default-value="$product->status" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب واحد" />
						<x-select name="unit_id" :data="$units" option-value="id" option-label="label"
							:default-value="$product->unit_id" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="قیمت (تومان)" />
						<x-input type="text" name="unit_price" class="comma" :default-value="number_format($product->unit_price)" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب نوع تخفیف" />
						<x-select name="discount_type" :data="$discountTypes" option-value="name" option-label="label"
							:default-value="$product->discount_type" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="تخفیف" />
						<x-input type="text" name="discount" class="comma" :default-value="number_format($product->discount)" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="زمان اتمام تخفیف" />
						<x-date-input id="discount_until" name="discount_until" :default-value="$product->discount_until" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="موجودی" />
						<x-input type="number" name="balance" :default-value="$product->store->balance" required />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="شارژ روزانه" />
						<x-input type="number" name="daily_balance" :default-value="$product->daily_balance" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	@push('scripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>