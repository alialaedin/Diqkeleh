<x-layouts.master title="سفارشات">

  <div class="page-header">

    <x-breadcrumb>
      <x-breadcrumb-item title="سفارشات" :route="route('admin.orders.index')" />
      <x-breadcrumb-item title=" جزئیات سفارش" />
    </x-breadcrumb>

    <div class="d-flex" style="gap: 6px;">
      {{-- <button class="btn btn-sm btn-warning" data-target="#edit-order-modal" data-toggle="modal">ویرایش
        سفارش</button> --}}
      <button class="btn btn-sm btn-secondary" data-target="#edit-order-status-modal" data-toggle="modal">تغییر
        وضعیت</button>
      <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-purple btn-sm">پرینت</a>
    </div>

  </div>

  @php

    $customer = $order->customer;
    $courier = $order->courier;
    $address = json_decode($order->address);

    $data = [
      'جزئیات سفارش' => [
        'شناسه سفارش' => $order->id,
        'تاریخ ثبت' => verta($order->created_at)->format('Y/m/d H:i'),
        'زمان ارسال' => $order->delivered_at ? verta($order->delivered_at)->format('Y/m/d H:i') : '-',
        'وضعیت سفارش' => $order->status->label(),
      ],
      'اطلاعات مشتری' => [
        'شناسه' => $customer->id,
        'نام' => $customer->first_name,
        'نام خانوادگی' => $customer->last_name,
        'موبایل' => $customer->mobile,
      ],
      'اطلاعات دریافت کننده' => [
        'نام' => $address->first_name,
        'نام خانوادگی' => $address->last_name,
        'موبایل' => $address->mobile,
        'آدرس' => $address->address,
      ],
      'اطلاعات پیک' => [
        'نام' => $courier?->first_name ?? '-',
        'نام خانوادگی' => $courier?->last_name ?? '-',
        'موبایل' => $courier?->mobile ?? '-',
        'نوع پیک' => $courier?->type->label() ?? '-',
      ],
    ];
  @endphp

  <x-row>
    @foreach ($data as $title => $details)
      <x-col lg="6" xl="3">
        <x-card :title="$title">
          <ul class="list-group">
            @foreach ($details as $title => $value)
              <li class="list-group-item p-2">
                <b class="fs-11">{{ $title }} :</b>
                <span class="fs-12">{{ $value }}</span>
              </li>
            @endforeach
          </ul>
        </x-card>
      </x-col>
    @endforeach
  </x-row>

  <x-card title="توضیحات">
    <x-row>
      @if ($order->description)
        <p>{{ $order->description }}</p>
      @else
        <p class="text-danger">توضیحی برای این سفارش ثبت نشده است !</p>
      @endif
    </x-row>
  </x-card>

  <x-card title="اقلام سفارش">

    <x-slot name="options">
      <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#add-new-item-modal">افزودن قلم جدید</button>
    </x-slot>

    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>محصول</th>
          <th>وضعیت</th>
          <th>مبلغ واحد (تومان)</th>
          <th>تخفیف واحد (تومان)</th>
          <th>تعداد</th>
          <th>مبلغ نهایی (تومان)</th>
          <th>عملیات</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        @foreach($order->items->sortByDesc('id') as $item)
          <tr>
            <td class="font-weight-bold">{{ $loop->iteration }}</td>
            <td>{{ $item->product->title }}</td>
            <td>
              <x-badge :type="$item->status->color()" :text="$item->status->label()" />
            </td>
            <td>{{ number_format($item->amount) }}</td>
            <td>{{ number_format($item->discount_amount) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->total_amount) }}</td>
            <td>
              <button data-item-id="{{ $item->id }}"
                data-update-quantity-url="{{ route('admin.order-items.update-quantity', $item) }}"
                class="btn btn-sm btn-dark edit-item-quantity-button">
                ویرایش تعداد
              </button>
              <button data-item-id="{{ $item->id }}"
                data-update-status-url="{{ route('admin.order-items.change-status', $item) }}" style="margin-left: 1px;"
                class="edit-item-status-button btn btn-sm btn-warning">
                تغییر وضعیت
              </button>
            </td>
          </tr>
        @endforeach
      </x-slot>
    </x-table>

    <x-row class="mx-4 justify-content-center" style="margin-top: 50px">
      <div class="col-12 col-xl-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <div class="row">
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>تعداد کالا ها</b>
                <span>{{ $order->items->count() }}</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>جمع اقلام</b>
                <span>{{ $order->items->sum('quantity') }}</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>مجموع قیمت کالا ها</b>
                <span>{{ number_format($order->items->sum('total_base_amount')) }} تومان</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>مجموع تخفیف روی کالا ها</b>
                <span>{{ number_format($order->items->sum('total_discount_amount')) }} تومان</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>تخفیف روی سفارش</b>
                <span>{{ number_format($order->discount_amount) }} تومان</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>هزینه ارسال</b>
                <span>{{ number_format($order->shipping_amount) }} تومان</span>
              </div>
              <div class="col-12 my-1 d-flex justify-content-between align-items-center">
                <b>جمع کل</b>
                <span>{{ number_format($order->total_amount) }} تومان</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </x-row>

  </x-card>

  <x-modal id="edit-order-status-modal" title="تغییر وضعیت سفارش">
    <x-form action="{{ route('admin.orders.change-status', $order) }}" method="PUT" :has-default-buttons="false">

      <x-row class="row">
        <x-col class="my-1">
          <b class="fs-15">وضعیت فعلی: </b>
          <span>{{ $order->status->label() }}</span>
        </x-col>
      </x-row>

      <x-row class="mt-3">
        <x-col>
          <x-form-group>
            <x-select name="status" :data="$statuses" option-value="name" option-label="label" />
          </x-form-group>
        </x-col>
      </x-row>

      <div class="modal-footer justify-content-center mt-2 py-0 border-0">
        <button class="btn btn-sm btn-warning" type="submit">بروزرسانی</button>
        <button class="btn btn-sm btn-outline-danger" data-dismiss="modal">انصراف</button>
      </div>

    </x-form>
  </x-modal>

  <x-modal id="add-new-item-modal" title="افزودن به سفارش">
    <x-form action="{{ route('admin.order-items.add-item', $order) }}" method="POST" :has-default-buttons="false">

      <x-col>
        <x-form-group>
          <x-select name="product_id" :data="$products" option-value="id" option-label="title"
            select-label="انتخاب محصول" />
        </x-form-group>
      </x-col>

      <x-col>
        <x-form-group>
          <x-input type="number" name="quantity" placeholder="تعداد" required />
        </x-form-group>
      </x-col>

      <div class="modal-footer justify-content-center mt-2">
        <button class="btn btn-sm btn-info" type="submit">افزودن</button>
        <button class="btn btn-sm btn-outline-danger" data-dismiss="modal">انصراف</button>
      </div>

    </x-form>
  </x-modal>

  <x-modal id="edit-item-quantity-modal" title="ویرایش تعداد آیتم سفارش">
    <form action="" method="POST">

      @csrf
      @method('PUT')

      <x-col class="mb-2">
        <span>تعداد فعلی : </span>
        <b class="old-quantity"></b>
      </x-col>

      <x-col>
        <x-form-group>
          <x-input type="number" placeholder="تعداد جدید را وارد کنید" name="quantity" required />
        </x-form-group>
      </x-col>

      <x-col class="col-12">
        <x-row class="justify-content-center" style="gap: 8px">
          <button class="btn btn-sm btn-warning" type="submit">بروزرسانی تعداد</button>
          <button class="btn btn-sm btn-outline-danger" data-dismiss="modal">انصراف</button>
        </x-row>
      </x-col>

    </form>
  </x-modal>

  <form id="update-item-status-form" class="d-none" action="" method="POST">
    @csrf
    @method('PUT')
  </form>

  @push('scripts')

    @stack('SelectComponentScripts')
    @stack('dateInputScriptStack')

    <script>

      function updateOrderItemStatus() {
        $('.edit-item-status-button').each(function () {

          $(this).click(() => {

            let updateUrl = $(this).data('update-status-url');

            Swal.fire({
              text: 'آیا تمایل دارید وضعیت آیتم را تغییر دهید',
              icon: "warning",
              confirmButtonText: 'تغییر وضعیت',
              showDenyButton: true,
              denyButtonText: 'انصراف',
              dangerMode: true,
            }).then((result) => {
              if (result.isConfirmed) {
                $('#update-item-status-form').attr('action', updateUrl);
                $('#update-item-status-form').submit();
              }
            });

          });

        });
      }

      function updateOrderItemQuantity() {

        const updateQuantityModal = $('#edit-item-quantity-modal');
        const updateQuantityForm = updateQuantityModal.find('form');

        $('.edit-item-quantity-button').each(function () {
          $(this).click(() => {

            const orderItem = @json($order->items).find(oi => oi.id == $(this).data('item-id'));
            const updateUrl = $(this).data('update-quantity-url');

            updateQuantityForm.find('.old-quantity').text(orderItem.quantity);
            updateQuantityForm.attr('action', updateUrl);

            updateQuantityModal.modal('show');
          });
        });
      }

      $(document).ready(() => {
        updateOrderItemStatus();
        updateOrderItemQuantity();
      });

    </script>

  @endpush

</x-layouts.master>