<x-layouts.master title="حقوق های پراخت شده">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="حقوق های پراخت شده" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت حقوق جدید" :route="route('admin.salaries.create')" />
  </div>

  <x-card title="حقوق های پراخت شده">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>نام کارمند</th>
          <th>شماره موبایل</th>
          <th>مبلغ (تومان)</th>
          <th>اضافه کاری (ساعت)</th>
          <th>تاریخ پرداخت</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($salaries as $salary)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $salary->employee->full_name }}</td>
            <td>{{ $salary->employee->mobile }}</td>
            <td>{{ number_format($salary->amount) }}</td>
            <td>{{ $salary->overtime ?? 0 }}</td>
            <td><x-jalali-date :date="$salary->paid_at" /></td>
            <td><x-jalali-date :date="$salary->created_at" /></td>
            <td>
              <x-edit-button :model="$salary" route="admin.salaries.edit" />
              <x-delete-button :model="$salary" route="admin.salaries.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="8" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

</x-layouts.master>