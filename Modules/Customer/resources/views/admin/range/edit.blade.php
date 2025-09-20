<x-layouts.master title="محدوده ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="محدوده ها" :route="route('admin.ranges.index')" />
			<x-breadcrumb-item title="ویرایش محدوده" />
		</x-breadcrumb>
	</div>

	<x-card title="ویرایش محدوده">
		<x-form :action="route('admin.ranges.update', $range)" method="PATCH">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="عنوان" />
						<x-input type="text" name="title" :default-value="$range->title" autofocus />
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label :is-required="true" text="هزینه ارسال (تومان)" />
						<x-input type="text" name="shipping_amount" class="comma" :default-value="number_format($range->shipping_amount)" />
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-checkbox name="status" title="وضعیت" :is-checked="$range->status" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>

</x-layouts.master>