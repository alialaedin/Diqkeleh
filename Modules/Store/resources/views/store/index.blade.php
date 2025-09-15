<x-layouts.master title="انبار">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="موجودی محصولات" />
		</x-breadcrumb>
		<div style="display: flex; gap: 8px;">
			<button id="increment-store-btn" class="btn btn-outline-success btn-sm">افزایش موجودی</button>
			<button id="decrement-store-btn" class="btn btn-outline-danger btn-sm">کاهش موجودی</button>
		</div>
	</div>

  <x-card title="جستجوی پیشرفته">
		<x-form :action="route('admin.stores.index')" method="GET">

			<x-row>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب محصول" />
						<x-select name="product_id" :data="$products" option-value="id" option-label="title" /> 
					</x-form-group>
				</x-col>

				<x-col lg="6" xl="3">
					<x-form-group>
						<x-label text="انتخاب دسته بندی" />
						<x-select name="category_id" :data="$categories" option-value="id" option-label="title" /> 
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
		<x-table :pagination="$stores">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان</th>
					<th>دسته بندی</th>
					<th>موجودی</th>
					<th>اولین تراکنش</th>
					<th>آخرین تراکنش</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($stores as $store)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $store->product->title }}</td>
						<td>{{ $store->product->category->title }}</td>
						<td>{{ $store->balance }}</td>
						<td><x-jalali-date :date="$store->created_at" /></td>
						<td><x-jalali-date :date="$store->updated_at" /></td>
					</tr>
				@empty
					<x-no-data :colspan="6" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	<x-modal id="increase-decrease-modal" title="انبار" size="md">
		<x-form action="{{ route('admin.stores.store') }}" method="PUT" :has-default-buttons="false">
			
			<x-input type="hidden" id="type" name="type" required/> 

			<x-row>

				<x-col>
					<x-form-group>
						<x-select 
							id="product2" 
							name="product_id" 
							:data="$products" 
							option-value="id" 
							option-label="title" 
							select-label="انتخاب محصول" 
							required
						/> 
					</x-form-group>
				</x-col>

				<x-col>
					<x-form-group>
						<x-input type="text" name="quantity" placeholder="تعداد" required/> 
					</x-form-group>
				</x-col>
				
				<x-col>
					<x-form-group>
						<x-textarea name="description" rows="2" placeholder="توضیحات" required/> 
					</x-form-group>
				</x-col>

			</x-row>

			<x-row class="justify-content-center" style="gap: 8px">
				<button class="btn btn-sm btn-primary disableable" type="submit">ثبت و ذخیره</button>
				<button class="btn btn-sm btn-danger" type="button" data-dismiss="modal">انصراف</button>
			</x-row>

		</x-form>
	</x-modal>

  @push('scripts')

		@stack('SelectComponentScripts')
		@stack('dateInputScriptStack')

		<script>

			$(document).ready(() => {
				$('#increment-store-btn').click(() => showModal('increment'));
				$('#decrement-store-btn').click(() => showModal('decrement'));
			});

			function showModal(type) {
				const modal = $('#increase-decrease-modal');
				let text = type == 'increment' ? 'اضافه کردن به انبار' : 'کم کردن از انبار';
				modal.find('.modal-title').text(text);
				modal.find('#type').val(type);
				modal.modal('show');
			}
		</script>

	@endpush

</x-layouts.master>