<x-layouts.master title="دسته بندی ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="دسته بندی ها" />
		</x-breadcrumb>
		<div class="d-flex" style="gap: 6px">
			<button id="sort-btn" class="btn btn-sm btn-azure">مرتب سازی</button>
			<x-link-create-button title="ثبت دسته بندی جدید" :route="route('admin.categories.create')" />
		</div>
	</div>

	<x-card title="دسته بندی ها">
		<x-table id="categories-table">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>عنوان فارسی</th>
					<th>عنوان انگیلیسی</th>
					<th>تعداد محصولات</th>
					<th>وضعیت</th>
					<th>تاریخ</th>
					<th>ساعت</th>
					<th>عملیات</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($categories as $category)
					<tr class="glyphicon-move" style="cursor: move">
						<td class="d-none sort-category-id" data-id="{{ $category->id }}"></td>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $category->title }}</td>
						<td>{{ $category->en_title }}</td>
						<td>{{ $category->products_count }}</td>
						<td>
							<x-badge :type="$category->status ? 'success' : 'danger'" :text="$category->status ? 'فعال' : 'غیر فعال'" />
						</td>
						<td><x-jalali-date :date="$category->created_at" format="date" /></td>
						<td><x-jalali-date :date="$category->created_at" format="time" /></td>
						<td>
							<x-edit-button :model="$category" route="admin.categories.edit" />
							<x-delete-button :model="$category" route="admin.categories.destroy"
								:disabled="$category->products_count" />
						</td>
					</tr>
				@empty
					<x-no-data :colspan="8" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

	@push('scripts')
		<script>

			const items = document.querySelector('#categories-table tbody');
			const sortable = Sortable.create(items, {
				handle: '.glyphicon-move',
				animation: 150
			});

			$(document).ready(() => {

				const sortBtn = $('#sort-btn');
				const categories = [];

				sortBtn.click(async () => {

					sortBtn.prop('disabled', true);

					$('#categories-table tbody tr').each(function () {
						categories.push($(this).find('.sort-category-id').data('id'));
					});

					try {
						const url = @json(route('admin.categories.sort'));
						const response = await fetch(url, {
							method: 'PUT',
							body: JSON.stringify({
								orders: categories
							}),
							headers: {
								'Accept': 'application/json',
								'Content-Type': 'application/json',
								'X-CSRF-TOKEN': @json(csrf_token())
							}
						});

						if (!response.ok) {
							throw new Error(`HTTP error! Status: ${response.status}`);
						}

						const result = await response.json();
						if (response.status === 402) {
							showValidationError(result.errors);
						} else if (response.status === 500) {
							popup('error', 'خطای سرور', result.message);
						} else if (response.status === 200) {
							popup('success', 'عملیات موفق', result.message);
						}

					} catch (error) {
						console.error('Error during fetch:', error.message);
					} finally {
						sortBtn.prop('disabled', false);
					}
				});
			});

		</script>
	@endpush

</x-layouts.master>