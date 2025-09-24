<x-layouts.master title="دسته بندی ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="دسته بندی ها" :route="route('admin.categories.index')" />
      <x-breadcrumb-item title="محصولات دسته بندی" />
    </x-breadcrumb>
    <div class="d-flex" style="gap: 6px">
      <button id="sort-btn" class="btn btn-sm btn-azure">مرتب سازی</button>
    </div>
  </div>

  <x-card title="محصولات دسته بندی">
    <x-table id="products-table">
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>عنوان</th>
          <th>قیمت پایه (تومان)</th>
          <th>تخفیف (تومان)</th>
          <th>قیمت نهایی (تومان)</th>
          <th>وضعیت</th>
          <th>تاریخ ثبت</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($category->products as $product)
          <tr class="glyphicon-move" style="cursor: move">
            <td class="d-none sort-product-id" data-id="{{ $product->id }}"></td>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ number_format($product->unit_price) }}</td>
            <td>{{ number_format($product->discount_amount) }}</td>
            <td>{{ number_format($product->final_price) }}</td>
            <td>
              <x-badge :type="$product->status->color()" :text="$product->status->label()" />
            </td>
            <td><x-jalali-date :date="$product->created_at" /></td>
          </tr>
        @empty
          <x-no-data :colspan="7" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

  @push('scripts')
    <script>

      const items = document.querySelector('#products-table tbody');
      const sortable = Sortable.create(items, {
        handle: '.glyphicon-move',
        animation: 150
      });

      $(document).ready(() => {

        const sortBtn = $('#sort-btn');
        const products = [];

        sortBtn.click(async () => {

          sortBtn.prop('disabled', true);

          $('#products-table tbody tr').each(function () {
            products.push($(this).find('.sort-product-id').data('id'));
          });

          try {
            const url = @json(route('admin.categories.sort-products', $category));
            const response = await fetch(url, {
              method: 'PUT',
              body: JSON.stringify({ products }),
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