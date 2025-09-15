<x-layouts.master title="تراکنش های انبار">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="تراکنش های انبار" />
		</x-breadcrumb>
	</div>

  <x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.store-transactions.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب محصول" />
						<x-select name="product_id" :data="$products" option-value="id" option-label="title" /> 
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="نوع تراکنش" />
						<x-select name="type" :data="$types" option-value="name" option-label="label" /> 
					</x-form-group>
				</x-col>

        <x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="از تاریخ" />
						<x-date-input id="start_date" name="start_date" />
					</x-form-group>
				</x-col>

        <x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="تا تاریخ" />
						<x-date-input id="end_date" name="end_date" />
					</x-form-group>
				</x-col>

			</x-row>

		</x-form>
	</x-card>
	
	<x-card title="محصولات">
		<x-table :pagination="$transactions">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>شناسه تراکنش</th>
					<th>محصول</th>
					<th>توضیحات</th>
					<th>تعداد</th>
					<th>نوع تغییرات</th>
					<th>تاریخ ثبت</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($transactions as $transaction)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $transaction->id }}</td>
						<td>{{ $transaction->store->product->title }}</td>
						<td style="white-space: wrap;">{{ $transaction->description }}</td>
						<td>{{ $transaction->quantity }}</td>
						<td>
							<x-badge 
								:type="$transaction->type->color()"
								:text="$transaction->type->label()"
							/>
						</td>
						<td><x-jalali-date :date="$transaction->created_at" /></td>
					</tr>
				@empty
					<x-no-data :colspan="7" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

  @push('scripts')
		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')
	@endpush

</x-layouts.master>