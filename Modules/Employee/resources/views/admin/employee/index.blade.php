<x-layouts.master title="کارمندان">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="کارمندان" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت کارمند جدید" :route="route('admin.employees.create')" />
  </div>

  <x-card title="کارمندان">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>نام و نام خانوادگی</th>
          <th>شماره موبایل</th>
          <th>کد ملی</th>
          <th>تلفن ثابت</th>
          <th>حقوق پایه (تومان)</th>
          <th>تاریخ استخدام</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($employees as $employee)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $employee->full_name }}</td>
            <td>{{ $employee->mobile }}</td>
            <td>{{ $employee->national_code }}</td>
            <td>{{ $employee->telephone ?? '-' }}</td>
            <td>{{ number_format($employee->base_salary) }}</td>
            <td><x-jalali-date :date="$employee->employmented_at" format="date" /></td>
            <td><x-jalali-date :date="$employee->created_at" /></td>
            <td>
              <a href="{{ route('admin.salaries.index', ['employee_id' => $employee->id]) }}" data-toggle="tooltip"
                data-original-title="حقوق های پرداخت شده" class="btn btn-sm btn-icon btn-lime"
                style="padding-inline: 10.5px">
                <i class="fa fa-dollar"></i>
              </a>
              <button data-id="{{ $employee->id }}" data-toggle="tooltip" data-original-title="آدرس"
                class="btn btn-sm btn-icon btn-purple show-address-modal" style="padding-inline: 10px">
                <i class="fa fa-map-marker"></i>
              </button>
              <x-show-button :model="$employee" route="admin.employees.show" />
              <x-edit-button :model="$employee" route="admin.employees.edit" />
              <x-delete-button :model="$employee" route="admin.employees.destroy" />
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
      $(document).ready(() => {
        const employees = @json($employees);
        $('.show-address-modal').each(function () {
          $(this).on('click', function () {
            const employeeId = $(this).data('id');
            const employee = employees.find(m => m.id == employeeId);
            Swal.fire({
              title: 'محل سکونت',
              text: employee?.address || 'توضیحی ثبت نشده است',
              icon: 'info',
              confirmButtonText: "بستن",
            });
          });
        });
      });
    </script>
  @endpush

</x-layouts.master>