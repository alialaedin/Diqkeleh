<x-layouts.master title="سرفصل ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="سرفصل ها" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت سرفصل جدید" :route="route('admin.headlines.create')" />
  </div>

  <x-card title="سرفصل ها">
    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>عنوان</th>
          <th>شناسه</th>
          <th>نوع</th>
          <th>وضعیت</th>
          <th>تاریخ ثبت</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($headlines as $headline)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $headline->title }}</td>
            <td>{{ $headline->id }}</td>
            <td>
              <x-badge :type="$headline->type->color()" :text="$headline->type->label()" :is-light="true" />
            </td>
            <td>
              <x-badge 
                :type="$headline->status ? 'success' : 'danger'" 
                :text="$headline->status ? 'فعال' : 'غیر فعال'" 
              />
            </td>
            <td><x-jalali-date :date="$headline->created_at" format="date" /></td>
            <td>
              <x-edit-button :model="$headline" route="admin.headlines.edit" />
              <x-delete-button :model="$headline" route="admin.headlines.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="7" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

</x-layouts.master>