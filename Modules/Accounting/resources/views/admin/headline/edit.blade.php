<x-layouts.master title="سرفصل ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="سرفصل ها" :route="route('admin.headlines.index')" />
			<x-breadcrumb-item title="ویرایش سرفصل" />
		</x-breadcrumb>
	</div>
	
	<x-card title="ویرایش سرفصل">
		<x-form :action="route('admin.headlines.update', $headline)" method="PATCH">
			<x-row>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان" />
						<x-input type="text" name="title" required autofocus :default-value="$headline->title" />
					</x-form-group>
				</x-col>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="نوع سرفصل" />
						<x-select name="type" :data="$types" option-value="name" option-label="label" :default-value="$headline->type->value" />
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-checkbox name="status" title="وضعیت" :is-checked="$headline->status" />
					</x-form-group>
				</x-col>
			</x-row>
		</x-form>
	</x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>