<x-layouts.master title="پرداختی ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="پرداختی ها" />
    </x-breadcrumb>
    <x-link-create-button title="ثبت پرداختی جدید" :route="route('admin.payments.create')" />
  </div>

  <x-card title="جستجوی پیشرفته">
    <x-form :action="route('admin.payments.index')" method="GET">

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="نام مشتری" />
            <x-input type="text" name="customer_name" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="موبایل مشتری" />
            <x-input type="text" name="customer_mobile" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="انتخاب نوع پرداخت" />
            <x-select name="status" :data="$types" option-value="name" option-label="label" />
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

  <x-card title="پرداختی ها">
    <x-table :pagination="$payments">
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>مشتری</th>
          <th>مبلغ (تومان)</th>
          <th>نوع پرداخت</th>
          <th>تاریخ ثبت</th>
          <th>تاریخ بروزرسانی</th>
          <th>تاریخ پرداخت</th>
          <th>توضیحات</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @forelse ($payments as $payment)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>
              <a href="{{ route('admin.customers.show', $payment->customer) }}" target="_blank">
                {{ $payment->customer->full_name . ' - ' . $payment->customer->mobile }}
              </a>
            </td>
            <td>{{ number_format($payment->amount) }}</td>
            <td>
              <x-badge :type="$payment->type->color()" :text="$payment->type->label()" :is-light="true" />
            </td>
            <td><x-jalali-date :date="$payment->created_at" /></td>
            <td><x-jalali-date :date="$payment->updated_at" /></td>
            <td>
              @if ($payment->paid_at)
                <x-jalali-date :date="$payment->paid_at" />
              @else
                <span>-</span>
              @endif
            </td>
            <td>
              <button class="btn btn-sm btn-icon btn-dark text-white show-description-modal" data-id="{{ $payment->id }}">
                <i class="fa fa-folder-o"></i>
              </button>
            </td>
            <td>
              <x-edit-button :model="$payment" route="admin.payments.edit" />
              <x-delete-button :model="$payment" route="admin.payments.destroy" />
            </td>
          </tr>
        @empty
          <x-no-data :colspan="9" desc="پرداختی ای یافت نشد !" />
        @endforelse
      </x-slot>
    </x-table>
  </x-card>

  @push('scripts')

    @stack('SelectComponentScripts')
    @stack('dateInputScriptStack')

    <script>
      $(document).ready(() => {
        const payments = @json($payments).data;
        $('.show-description-modal').each(function () {
          $(this).on('click', function () {
            const paymentId = $(this).data('id');
            const payment = payments.find(m => m.id == paymentId);
            Swal.fire({
              title: 'توضیحات پرداختی',
              text: payment?.description || 'توضیحی ثبت نشده است',
              icon: 'info',
              confirmButtonText: "بستن",
            });
          });
        });
      });
    </script>

  @endpush

</x-layouts.master>