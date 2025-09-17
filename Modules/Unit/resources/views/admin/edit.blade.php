<x-layouts.master title="واحد ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="واحد ها" :route="route('admin.units.index')" />
			<x-breadcrumb-item title="ویرایش واحد" />
		</x-breadcrumb>
	</div>
	
	<x-card title="ویرایش واحد">
		<x-form :action="route('admin.units.update', $unit)" method="PATCH">
			<x-row>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (فارسی)" />
						<x-input type="text" name="label":deafult-value="$unit->label" required autofocus />
					</x-form-group>
				</x-col>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (انگیلیسی)" />
						<x-input type="text" name="name":deafult-value="$unit->name" required />
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-checkbox name="status" title="وضعیت" :is-checked="$unit->status" />
					</x-form-group>
				</x-col>
			</x-row>
		</x-form>
	</x-card>

</x-layouts.master>