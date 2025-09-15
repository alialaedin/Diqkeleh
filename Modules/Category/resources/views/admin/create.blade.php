<x-layouts.master title="دسته بندی ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="دسته بندی ها" :route="route('admin.categories.index')" />
			<x-breadcrumb-item title="ثبت دسته بندی جدید" />
		</x-breadcrumb>
	</div>
	
	<x-card title="دسته بندی جدید">
		<x-form :action="route('admin.categories.store')" method="POST">
			<x-row>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (فارسی)" />
						<x-input type="text" name="title" required autofocus />
					</x-form-group>
				</x-col>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (انگیلیسی)" />
						<x-input type="text" name="en_title" required />
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-label text="توضیحات" />
						<x-textarea name="description"/>
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-checkbox name="status" title="وضعیت" :is-checked="1" />
					</x-form-group>
				</x-col>
			</x-row>
		</x-form>
	</x-card>

</x-layouts.master>