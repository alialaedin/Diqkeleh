<x-layouts.master title="گزارش فروش امروز">

  <div class="page-header d-print-none">
    <x-breadcrumb>
      <x-breadcrumb-item title="گزارش فروش امروز" />
    </x-breadcrumb>
  </div>

  <x-card title="گزارش فروش امروز">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>شناسه محصول</th>
          <th>عنوان</th>
          <th>تعداد فروش</th>
          <th>میزان فروش خالص (تومان)</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($products as $product)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ number_format($product->sales_count) }}</td>
            <td>{{ number_format($product->sales_amount) }}</td>
          </tr>
        @empty
          <x-no-data :colspan="5" />
        @endforelse
        @if ($products->isNotEmpty())
          <tr class="font-weight-bold fs-14">
            <td colspan="3">جمع کل :</td>
            <td>{{ number_format($products->sum('sales_count')) }}</td>
            <td>{{ number_format($products->sum('sales_amount')) }}</td>
          </tr>
        @endif
      </x-slot>
    </x-table>
  </x-card>

</x-layouts.master>