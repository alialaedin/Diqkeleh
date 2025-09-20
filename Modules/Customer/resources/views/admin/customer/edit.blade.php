<x-layouts.master title="مشتریان">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="مشتریان" :route="route('admin.customers.index')" />
			<x-breadcrumb-item title="ویرایش مشتری" />
		</x-breadcrumb>
	</div>

	<x-card title="ویرایش مشتری">
		<x-form :action="route('admin.customers.update', $customer)" method="PATCH">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="نام و نام خانوادگی" />
						<x-input type="text" name="full_name" :default-value="$customer->full_name" autofocus />
					</x-form-group>
				</x-col>

        <x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="شماره همراه" />
						<x-input type="text" name="mobile" :default-value="$customer->mobile" required />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب وضعیت" />
						<x-select name="status" :data="$statuses" option-value="value" option-label="label" :default-value="$customer->status->value" /> 
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	@push('scripts')
		@stack('SelectComponentScripts')
	@endpush

</x-layouts.master>