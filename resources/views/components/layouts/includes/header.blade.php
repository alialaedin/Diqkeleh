<div class="app-header header">
  <div class="container-fluid">
    <div class="d-flex">
      <div class="app-sidebar__toggle" data-toggle="sidebar">
        <a class="open-toggle" href="#">
          <i class="feather feather-menu"></i>
        </a>
        <a class="close-toggle" href="#">
          <i class="feather feather-x"></i>
        </a>
      </div>
      <div class="d-flex  my-auto mr-auto" style="gap: 8px;">
        <a class="btn btn-light" href="{{ route('admin.store-multi-charge.index') }}">شارژ محصولات</a>
        <a class="btn btn-light" href="{{ route('admin.orders.index') }}">همه سفارشات</a>
        <a class="btn btn-light" href="{{ route('admin.orders.create') }}">سفارش جدید</a>
        <a class="btn btn-light" href="{{ route('admin.orders.today-orders') }}">سفارشات امروز</a>
        <button onclick="event.preventDefault();document.getElementById('logout-form').submit();"
          class="btn btn-red">خروج
        </button>
      </div>
    </div>
  </div>
</div>

<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
  @csrf
</form>