<x-layouts.master title="دسته بندی ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="دسته بندی ها" :route="route('admin.categories.index')" />
			<x-breadcrumb-item title="ویرایش دسته بندی" />
		</x-breadcrumb>
	</div>
	
	<x-card title="ویرایش دسته بندی">
		<x-form :action="route('admin.categories.update', $category)" method="PATCH">
			<x-row>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (فارسی)" />
						<x-input type="text" name="title" :default-value="$category->title" required autofocus />
					</x-form-group>
				</x-col>
				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان (انگیلیسی)" />
						<x-input type="text" name="en_title" :default-value="$category->en_title" required />
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-label text="توضیحات" />
						<x-textarea name="description" :default-value="$category->description" />
					</x-form-group>
				</x-col>
				<x-col>
					<x-form-group>
						<x-checkbox name="status" title="وضعیت" :is-checked="$category->status" />
					</x-form-group>
				</x-col>
			</x-row>
		</x-form>
	</x-card>

</x-layouts.master>