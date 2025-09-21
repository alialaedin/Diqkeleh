<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .section-print {
      padding: 70px;
    }

    media (max-width:1100px) {
      .section-print {
        padding: 50px;
      }
    }

    media (max-width:680px) {
      .section-print {
        padding: 25px;
      }
    }

    .print-table {
      display: table;
    }

    .print-table td {
      border: 1px solid #cdcbcb;
      padding: 5px 10px;
      vertical-align: top;
      font-size: 14px;
      text-align: center;
    }

    .print-table th {
      border: 1px solid #cdcbcb;
      background: #f0f0f0;
      font-weight: bold;
      text-align: right;
      padding: 5px 10px;
      text-align: center;
    }

    .btn-print {
      background-color: rgb(0, 102, 255);
      color: white;
      border-radius: 10px;
    }

    .section-title {
      background-color: gray;
      color: white;
      margin-bottom: 10px;
    }

    media print {
      page {
        size: 80mm 210mm;
        margin: 4mm 4mm 4mm 4mm;
        /* adjust margins to printer specs */
      }

      body {
        margin: 0;
        padding: 0;
        font-family: Tahoma, Arial, sans-serif;
        font-size: 9pt;
        direction: rtl;
        /* if Persian */
        color: #000;
      }

      table {
        width: 72.1mm;
        /* printable width */
        border-collapse: collapse;
        table-layout: fixed;
        word-wrap: break-word;
        margin: 0 auto;
      }

      th,
      td {
        border: 1px solid #000;
        padding: 2px 4px;
        max-width: 24mm;
        /* depending on number of columns */
        text-align: right;
        overflow-wrap: break-word;
      }

      tr {
        page-break-inside: avoid;
      }

      .no-print {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <main>
    <section class="section-print my-5 d-none d-print-block">
      <h2 class=" mb-4">شماره سفارش: {{ $order->id }}</h2>
      <div class="d-flex flex-column">
        <div clas="d-flex align-items-center">
          <span class="fs-13">تاریخ: </span>
          <time class="font-weight-bold fs-14">{{ verta($order->created_at)->formatDate() }}</time>
        </div>
        <div clas="d-flex align-items-center">
          <span class="fs-13">شماره فاکتور: </span>
          <span class="font-weight-bold fs-14">{{ $order->id }}</span>
        </div>
        <div clas="d-flex align-items-center">
          <span class="fs-13">موبایل مشتری: </span>
          <span class="font-weight-bold fs-14">{{ $order->customer->mobile }}</span>
        </div>
      </div>
      <h3 class="section-title text-center p-1 mt-4">اقلام سفارش</h3>
      <table class="print-table table d-table w-100">
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
          @foreach ($orders->items as $item)
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
      <table class="print-table table d-table w-100">
        <tbody>
          <tr>
            <td>تعداد کالا</td>
            <td>{{ $order->items->count() }}</td>
          </tr>
          <tr>
            <td>مجموع قیمت کالا (تومان)</td>
            <td>{{ number_format($order->items->sum('total_base_amount')) }}</td>
          </tr>
          <tr>
            <td>تخفیف (تومان)</td>
            <td>{{ number_format($order->items->sum('total_discount_amount')) }}</td>
          </tr>
        </tbody>
      </table>
      @if ($order->address->isNotEmpty())
        <div class="d-flex flex-column" style="gap: 4px">
          <div clas="d-flex align-items-center">
            <span class="fs-13">محدوده: </span>
            <time class="font-weight-bold fs-14">{{ $order->address?->range->title }}</span>
          </div>
          <div clas="d-flex align-items-center">
            <span class="fs-13">آدرس: </span>
            <span class="font-weight-bold fs-14">{{ $order->address?->address }}</span>
          </div>
        </div>
      @endif
      @if ($order->description)
        <div class="mt-3">{{ $order->description }}</div>
      @endif
    </section>
  </main>
</body>

</html>