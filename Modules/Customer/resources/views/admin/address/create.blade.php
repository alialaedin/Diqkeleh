<x-layouts.master title="آدرس ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="آدرس ها" :route="route('admin.addresses.index')" />
			<x-breadcrumb-item title="ثبت آدرس جدید" />
		</x-breadcrumb>
	</div>

	<x-card title="آدرس جدید">
		<x-form :action="route('admin.addresses.store')" method="POST">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب مشتری" />
						<select name="customer_id" id="customer_id" class="form-control fs-12">
							<option value="">انتخاب</option>
							@foreach ($customers as $customer)
								<option 
									value="{{ $customer->id }}" 
									@selected(old('customer_id') == $customer->id || request('customer_id') == $customer->id)>
									{{ $customer->mobile . ' | ' . $customer->full_name }}
								</option>	
							@endforeach
						</select>
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="نام" />
						<x-input type="text" name="first_name" required autofocus />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="نام خانوادگی" />
						<x-input type="text" name="last_name" required />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="شماره همراه" />
						<x-input type="text" name="mobile" required />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="آدرس" />
						<x-textarea name="address" :rows="3" required />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	@push('scripts')
		<script>
			$('#customer_id').select2({
				placeholder: 'انتخاب مشتری',
				allowClear: true,
			});
		</script>
	@endpush

</x-layouts.master>