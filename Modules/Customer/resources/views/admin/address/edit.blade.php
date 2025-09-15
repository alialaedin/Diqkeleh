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
						<x-input type="text" name="s" readonly value="{{ $address->customer->full_name .' - '. $address->customer->mobile }}" />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="نام" />
						<x-input type="text" name="first_name" :default-value="$address->first_name" required autofocus />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="نام خانوادگی" />
						<x-input type="text" name="last_name" :default-value="$address->last_name" required />
					</x-form-group>
				</x-col>

        <x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="شماره همراه" />
						<x-input type="text" name="mobile" :default-value="$address->mobile" required />
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

</x-layouts.master>