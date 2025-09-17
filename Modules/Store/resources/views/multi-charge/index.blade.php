<x-layouts.master title="انبار">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="شارژ گروهی محصولات" />
    </x-breadcrumb>
  </div>

  <x-card title="محصولات">
    <x-form :action="route('admin.store-multi-charge.update')" method="PATCH">
      <x-table>
        <x-slot name="thead">
          <tr>
            <th>ردیف</th>
            <th>دسته بندی</th>
            <th>عنوان محصول</th>
            <th>موجودی فعلی</th>
            <th>موجودی جدید</th>
          </tr>
        </x-slot>
        <x-slot name="tbody">
          @forelse ($products as $product)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $product->category->title }}</td>
              <td>{{ $product->title }}</td>
              <td>{{ $product->store->balance }}</td>
              <td>
                <x-input type="hidden" :name="'products[' . $loop->iteration . '][id]'" :default-value="$product->id" />
                <x-input type="hidden" :name="'products[' . $loop->iteration . '][current_balance]'" :default-value="$product->store->balance" />
                <x-input type="number" :name="'products[' . $loop->iteration . '][new_balance]'" />
              </td </tr>
          @empty
              <x-no-data :colspan="5" />
            @endforelse
        </x-slot>
      </x-table>
    </x-form>
  </x-card>

</x-layouts.master>