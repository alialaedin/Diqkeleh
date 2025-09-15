<x-layouts.master title="پرداختی ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="پرداختی ها" :route="route('admin.payments.index')" />
			<x-breadcrumb-item title="ثبت پرداختی جدید" />
		</x-breadcrumb>
	</div>

	<x-card title="پرداختی جدید">
		<x-form :action="route('admin.payments.store')" method="POST">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب مشتری" />
						<x-customer-seach />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب نوع پرداخت" />
						<x-select name="type" :data="$types" option-value="name" option-label="label" /> 
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="میزان پرداختی (تومان)" />
						<x-input type="text" class="comma" name="amount" required />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="زمان پرداخت" />
						<x-date-input id="paid_at" name="paid_at" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label text="توضیحات" />
						<x-textarea name="description" rows="4" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	@push('scripts')
		@stack('CustomerSearchScripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>