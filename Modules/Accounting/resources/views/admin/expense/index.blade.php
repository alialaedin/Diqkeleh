<x-layouts.master title="هزینه ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="هزینه ها" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت هزینه جدید" :route="route('admin.expenses.create')" />
  </div>

  <x-card title="هزینه ها">
    <x-table :pagination="$expenses">
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>کد</th>
          <th>سرفصل</th>
          <th>عنوان</th>
          <th>مبلغ (تومان)</th>
          <th>تاریخ پرداخت</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($expenses as $expense)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $expense->id }}</td>
            <td>{{ $expense->headline->title }}</td>
            <td>{{ $expense->title }}</td>
            <td>{{ number_format($expense->amount) }}</td>
            <td><x-jalali-date :date="$expense->payment_date" /></td>
            <td><x-jalali-date :date="$expense->created_at" /></td>
            <td>
              <x-edit-button :model="$expense" route="admin.expenses.edit" />
              <x-delete-button :model="$expense" route="admin.expenses.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="8" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

</x-layouts.master>