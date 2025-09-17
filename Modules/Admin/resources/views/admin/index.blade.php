<x-layouts.master title="ادمین ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="ادمین ها" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت ادمین جدید" :route="route('admin.admins.create')" />
  </div>

  <x-card title="ادمین ها">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>شناسه</th>
          <th>نام و نام خانوادگی</th>
          <th>نام کاربری</th>
          <th>شماره موبایل</th>
          <th>نقش</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($admins as $admin)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $admin->id }}</td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->username }}</td>
            <td>{{ $admin->mobile }}</td>
            <td>{{ $admin->role->label }}</td>
            <td><x-jalali-date :date="$admin->created_at" /></td>
            <td>
              <x-show-button :model="$admin" route="admin.admins.show" />
              <x-edit-button :model="$admin" route="admin.admins.edit" />
              <x-delete-button :model="$admin" route="admin.admins.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="9" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
    @stack('dateInputScriptStack')
  @endpush

</x-layouts.master>