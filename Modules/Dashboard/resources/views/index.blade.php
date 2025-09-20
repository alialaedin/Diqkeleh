<x-layouts.master>

  <div class="page-header">
    <x-breadcrumb />
    <div class="d-flex align-items-center" style="gap: 6px">
      <a class="btn btn-sm btn-blue" href="{{ route('admin.store-multi-charge.index') }}">شارژ محصولات</a>
      <a class="btn btn-sm btn-azure" href="{{ route('admin.orders.create') }}">ثبت سفارش</a>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-lg-12 col-md-12">
      <x-card title="سفارشات حضوری">
        <div class="table-responsive attendance_table mt-4 border-top text-center fs-12 table-sm">
          <table class="table mb-0 text-nowrap">
            <thead>
              <tr>
                <th>شناسه</th>
                <th>مشتری</th>
                <th>زمان ثبت</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($inPersonOrders as $order)
                <tr class="border-bottom">
                  <td>
                    <span class="avatar avatar-sm brround">{{ $order->id }}</span>
                  </td>
                  <td>
                    <a href="{{ route('admin.customers.show', $order->customer) }}" target="_blank">
                      {{ $order->customer->full_name . ' - ' . $order->customer->mobile }}
                    </a>
                  </td>
                  <td>{{ $order->created_at->diffForHumans() }}</td>
                  <td>
                    <button class="send-in-person-btn btn btn-sm btn-icon btn-lime" data-toggle="tooltip"
                      data-original-title="تحویل داده شد" data-id="{{ $order->id }}" @disabled(!$order->isNew())>
                      <i class="fa fa-send"></i>
                    </button>
                    <button class="cancel-btn btn btn-sm btn-icon btn-red" data-toggle="tooltip"
                      data-original-title="کنسل کردن سفارش" data-id="{{ $order->id }}" @disabled(!$order->isNew())>
                      <i class="fa fa-times-circle-o"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </x-card>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12">
      <x-card title="سفارشات تلفنی">
        <div class="table-responsive attendance_table mt-4 border-top text-center fs-12 table-sm">
          <table class="table mb-0 text-nowrap">
            <thead>
              <tr>
                <th>شناسه</th>
                <th>مشتری</th>
                <th>زمان ثبت</th>
                <th>عملیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($telephoneOrders as $order)
                <tr class="border-bottom">
                  <td>
                    <span class="avatar avatar-sm brround">{{ $order->id }}</span>
                  </td>
                  <td>
                    <a href="{{ route('admin.customers.show', $order->customer) }}" target="_blank">
                      {{ $order->customer->full_name . ' - ' . $order->customer->mobile }}
                    </a>
                  </td>
                  <td>{{ $order->created_at->diffForHumans() }}</td>
                  <td>
                    <button class="send-btn btn btn-sm btn-icon btn-lime" data-toggle="tooltip"
                      data-original-title="ارسال" data-id="{{ $order->id }}" @disabled(!$order->isNew())>
                      <i class="fa fa-send"></i>
                    </button>
                    <button class="cancel-btn btn btn-sm btn-icon btn-red" data-toggle="tooltip"
                      data-original-title="کنسل کردن سفارش" data-id="{{ $order->id }}" @disabled(!$order->isNew())>
                      <i class="fa fa-times-circle-o"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </x-card>
    </div>
  </div>

  @foreach ($inPersonOrders as $order)

    <x-form id="deliver-order-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
      :has-default-buttons="false" method="PATCH" class="d-none">
      <input type="hidden" name="status" value="delivered" />
    </x-form>

    <x-form id="cancel-order-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
      :has-default-buttons="false" method="PATCH" class="d-none">
      <input type="hidden" name="status" value="canceled" />
    </x-form>

  @endforeach

  @foreach ($telephoneOrders as $order)

    <x-modal id="send-sms-modal-{{ $order->id }}" title="ارسال غذا" size="md">
      <x-form action="{{ route('admin.orders.change-status', $order) }}" :has-default-buttons="false" method="PATCH">

        <input type="hidden" name="status" value="delivered" />

        <fieldset class="form-group">
          <x-label text="انتخاب پیک" />
          <x-select name="courier_id" :data="$couriers" option-value="id" option-label="full_name" />
        </fieldset>

        <x-row>
          <x-col>
            <button type="submit" class="btn btn-success btn-block">ارسال</button>
            <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">بستن</button>
          </x-col>
        </x-row>

      </x-form>
    </x-modal>

    <x-form id="cancel-order-{{ $order->id }}" action="{{ route('admin.orders.change-status', $order) }}"
      :has-default-buttons="false" method="PATCH" class="d-none">
      <input type="hidden" name="status" value="canceled" />
    </x-form>

  @endforeach

  @push('scripts')
    <script>
      $(document).ready(() => {
        $('.send-in-person-btn').each(function () {
          $(this).click(() => {
            $('#deliver-order-' + $(this).data('id')).submit();
          });
        });
        $('.send-btn').each(function () {
          $(this).click(() => {
            $('#send-sms-modal-' + $(this).data('id')).modal('show');
          });
        });
        $('.cancel-btn').each(function () {
          $(this).click(() => {
            Swal.fire({
              title: "آیا مطمئن هستید؟",
              text: "بعد از کنسل کردن سفارش دیگر نمی توانید آن را ویرایش کنید!",
              icon: "warning",
              showCancelButton: true,
              showCloseButton: true,
              confirmButtonColor: "#d33",
              cancelButtonColor: "#3085d6",
              confirmButtonText: "کنسل کردن سفارش",
              cancelButtonText: "انصراف",
              dangerMode: true,
              customClass: {
                popup: 'sweet-alert-size'
              }
            }).then((result) => {
              if (result.isConfirmed) {
                $('#cancel-order-' + $(this).data('id')).submit();
              }
            });
          });
        });
      });	
    </script>
  @endpush

</x-layouts.master>