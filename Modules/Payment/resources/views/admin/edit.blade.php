<x-layouts.master title="ویرایش پرداختی">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="پرداختی ها" :route="route('admin.payments.index')" />
			<x-breadcrumb-item title="ویرایش پرداختی" />
		</x-breadcrumb>
	</div>

	<x-card title="ویرایش پرداختی">
		<x-form :action="route('admin.payments.update', $payment)" method="PATCH">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="مشتری" />
						<x-input type="text" name="customer_id" readonly :value="$payment->customer->full_name .' - '. $payment->customer->mobile" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب نوع پرداخت" />
						<x-select name="type" :data="$types" option-value="name" option-label="label" :default-value="$payment->type->value" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="میزان پرداختی (تومان)" />
						<x-input type="text" class="comma" name="amount" required :default-value="number_format($payment->amount)" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="زمان پرداخت" />
						<x-date-input id="paid_at" name="paid_at" :default-value="$payment->paid_at" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label text="توضیحات" />
						<x-textarea name="description" rows="4" :default-value="$payment->description" />
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