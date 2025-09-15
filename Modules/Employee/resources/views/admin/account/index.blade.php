<x-layouts.master title="حساب های بانکی">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="حساب های بانکی" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت حساب بانکی جدید" :route="route('admin.accounts.create')" />
  </div>

  <x-card title="حساب های بانکی">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>کارمند</th>
          <th>شماره موبایل</th>
          <th>شماره کارت</th>
          <th>نام بانک</th>
          <th>شماره شبا</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($accounts as $account)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $account->employee->full_name }}</td>
            <td>{{ $account->employee->mobile }}</td>
            <td>{{ $account->card_number }}</td>
            <td>{{ $account->bank_name }}</td>
            <td>{{ $account->sheba_number }}</td>
            <td><x-jalali-date :date="$account->created_at" /></td>
            <td>
              <x-edit-button :model="$account" route="admin.accounts.edit" />
              <x-delete-button :model="$account" route="admin.accounts.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="8" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

</x-layouts.master>