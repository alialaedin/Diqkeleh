<x-layouts.master title="آدرس ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="آدرس ها" :route="route('admin.addresses.index')" />
			<x-breadcrumb-item title="ویرایش آدرس" />
		</x-breadcrumb>
	</div>

	<x-card title="ویرایش آدرس">
		<x-form :action="route('admin.addresses.update', $address)" method="PATCH">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب مشتری" />
						<x-input type="text" readonly value="{{ $address->customer->full_name }}" name="s" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="انتخاب محدوده" />
						<select name="range_id" id="range_id" class="form-control fs-12">
							<option value="">انتخاب</option>
							@foreach ($ranges as $range)
								<option value="{{ $range->id }}" @selected(old('range_id', $address->range_id) == $range->id)>
									{{ $range->title }}
								</option>
							@endforeach
						</select>
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="شماره همراه" />
						<x-input type="text" name="mobile" :default-value="$address->mobile" required />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="کد پستی" />
						<x-input type="text" name="postal_code" :default-value="$address->postal_code" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-label :is-required="true" text="آدرس" />
						<x-textarea name="address" :rows="3" required :default-value="$address->address" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

	@push('scripts')
		<script>
			new CustomSelect('#range_id', 'انتخاب محدوده');
		</script>
	@endpush

</x-layouts.master>