<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link href="{{ asset('assets/font/font.css')}}" rel="stylesheet" />

  <style>
    .section-print {
      padding: 70px;
    }

    @media (max-width:1100px) {
      .section-print {
        padding: 50px;
      }
    }

    @media (max-width:680px) {
      .section-print {
        padding: 25px;
      }
    }

    @media print {
      .section-print {
        padding: 0;
      }
    }

    .table {
      display: table;
    }

    .table td {
      border: 1px solid black;
      padding: 5px 3px;
      vertical-align: top;
      font-size: 9.5px;
      text-align: center;
    }

    .table th {
      border: 1px solid black;
      background: #f0f0f0;
      font-weight: bold;
      text-align: right;
      padding: 5px 3px;
      text-align: center;
      font-size: 9.5px;
    }

    .btn-print {
      background-color: rgb(0, 102, 255);
      color: white;
      border-radius: 10px;
    }

    @media print {
      .btn-print {
        display: none;
      }
    }

    .section-title {
      margin-bottom: 10px;
    }

    .d-flex {
      display: -ms-flexbox !important;
      display: flex !important;
    }

    .flex-column {
      -ms-flex-direction: column !important;
      flex-direction: column !important;
    }

    .justify-content-between {
      -ms-flex-pack: justify !important;
      justify-content: space-between !important;
    }

    .align-items-center {
      -ms-flex-align: center !important;
      align-items: center !important;
    }

    .text-center {
      text-align: center !important;
    }

    .mb-4,
    .my-4 {
      margin-bottom: 1.5rem !important;
    }

    .mt-4,
    .my-4 {
      margin-top: 1.5rem !important;
    }

    .pr-5,
    .px-5 {
      padding-right: 3rem !important;
    }

    .p-1 {
      padding: 0.25rem !important;
    }

    .pt-1,
    .py-1 {
      padding-top: 0.25rem !important;
    }

    .fs-13 {
      font-size: 13px !important;
    }

    .fs-14 {
      font-size: 14px !important;
    }

    .font-weight-bold {
      font-weight: bold !important;
    }

    .w-100 {
      width: 100% !important;
    }
  </style>
</head>

<body>
  <main class="">
    <section class="section-print my-5">
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="text-center mb-4">شماره سفارش: {{ $order->id }}</h2>
        <button class="btn-print mt-4 px-5 py-1 align-items-center" onclick="window.print()">چاپ فاکتور</button>
      </div>
      <div class="d-flex flex-column">
        <div class="d-flex align-items-center">
          <span class="fs-13">تاریخ: </span>
          <time class="font-weight-bold fs-14">{{ verta($order->created_at)->formatDate() }}</time>
        </div>
        <div class="d-flex align-items-center">
          <span class="fs-13">مشتری: </span>
          <span class="font-weight-bold fs-14">{{ $order->customer->full_name }}</span>
        </div>
        <div class="d-flex align-items-center">
          <span class="fs-13">موبایل مشتری: </span>
          <span class="font-weight-bold fs-14">{{ $order->customer->mobile }}</span>
        </div>
      </div>
      <h3 class="section-title text-center p-1 mt-4">اقلام سفارش</h3>
      <table class="table d-table w-100">
        <thead>
          <tr>
            <th>ردیف</th>
            <th>عنوان</th>
            <th>تعداد</th>
            <th>قیمت</th>
            <th>تخفیف</th>
            <th>جمع کل</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->activeItems as $item)
            <tr>
              <td class="font-weight-bold">{{ $loop->iteration }}</td>
              <td>{{ $item->product->title }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->amount) }}</td>
              <td>{{ number_format($item->discount_amount) }}</td>
              <td>{{ number_format($item->total_amount) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <table class="table d-table w-100">
        <tbody>
          <tr>
            <td>تعداد کالا</td>
            <td>{{ $order->activeItems->sum('quantity') }}</td>
          </tr>
          <tr>
            <td>مجموع کالا ها (تومان)</td>
            <td>{{ number_format($order->activeItems->sum('total_amount')) }}</td>
          </tr>
          <tr>
            <td>تخفیف (تومان)</td>
            <td>{{ number_format($order->discount_amount) }}</td>
          </tr>
          <tr>
            <td>هزینه ارسال (تومان)</td>
            <td>{{ number_format($order->shipping_amount) }}</td>
          </tr>
          <tr>
            <td>مبلغ قابل پرداخت (تومان)</td>
            <td>{{ number_format($order->total_amount) }}</td>
          </tr>
        </tbody>
      </table>
      @if ($order->address_id)
        <div class="d-flex flex-column mt-4" style="gap: 4px">
          <div class="d-flex align-items-center">
            <span class="fs-13">آدرس: </span>
            <span class="font-weight-bold fs-14">{{ json_decode($order->address)?->address }}</span>
          </div>
        </div>
      @endif
      @if ($order->description)
        <div class="mt-4">{{ $order->description }}</div>
      @endif
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      window.print();
    });
  </script>

</body>

</html>