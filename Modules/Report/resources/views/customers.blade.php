<x-layouts.master title="گزارش مشتریان">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="گزارش مشتریان" />
    </x-breadcrumb>
  </div>

  <x-card title="جستجوی پیشرفته">
    <x-form :action="route('admin.reports.customers')" method="GET">

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="انتخاب مشتری" />
            <x-customer-seach />
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

  <x-card title="گزارش مشتریان">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>نام و نام خانوادگی</th>
          <th>موبایل</th>
          <th>تاریخ ثبت</th>
          <th>تعداد سفارشات</th>
          <th>تعداد پرداختی ها</th>
          <th>میزان خرید (تومان)</th>
          <th>پرداختی (تومان)</th>
          <th>باقی مانده (تومان)</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($customers as $customer)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $customer->full_name }}</td>
            <td>{{ $customer->mobile }}</td>
            <td><x-jalali-date :date="$customer->created_at" /></td>
            <td>{{ $customer->active_orders_count }}</td>
            <td>{{ $customer->payments_count }}</td>
            <td>{{ number_format($customer->total_sales_amount) }}</td>
            <td>{{ number_format($customer->total_payment_amount) }}</td>
            <td>{{ number_format($customer->remaining_amount) }}</td>
          </tr>
        @empty
          <x-no-data :colspan="9" />
        @endforelse
        @if ($customers->isNotEmpty())
          <tr class="font-weight-bold fs-14">
            <td colspan="4">جمع کل :</td>
            <td>{{ number_format($customers->sum('active_orders_count')) }}</td>
            <td>{{ number_format($customers->sum('payments_count')) }}</td>
            <td>{{ number_format($customers->sum('total_sales_amount')) }}</td>
            <td>{{ number_format($customers->sum('total_payment_amount')) }}</td>
            <td>{{ number_format($customers->sum('remaining_amount')) }}</td>
          </tr>
        @endif
      </x-slot>
      {{-- <x-slot name="extraData">
        {{ $customers->onEachSide(0) }}
      </x-slot> --}}
    </x-table>
  </x-card>

  @push('scripts')
    @stack('CustomerSearchScripts')
    @stack('dateInputScriptStack')
  @endpush

</x-layouts.master>