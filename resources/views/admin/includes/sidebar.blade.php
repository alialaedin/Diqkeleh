<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <div class="dropdown user-pro-body text-center">
      <div class="user-info">
        <span class="text-light fs-14">پنل مدیریت</span>
      </div>
    </div>
  </div>
  <div class="app-sidebar3 mt-0">
    <ul class="side-menu">

      <li class="slide">
        <a class="side-menu__item" href="{{route("admin.dashboard")}}">
          <i class="fe fe-home sidemenu_icon"></i>
          <span class="side-menu__label">داشبورد</span>
        </a>
      </li>

      @canany(['read_products', 'read_categories'])
        <li class="slide">
          <a class="side-menu__item" data-toggle="slide" href="#">
            <i class="fe fe-shopping-bag sidemenu_icon"></i>
            <span class="side-menu__label">محصولات</span><i class="angle fa fa-angle-left"></i>
          </a>
          <ul class="slide-menu">
            @can('read_categories')
              <li><a href="{{ route('admin.categories.index')}}" class="slide-item">دسته بندی ها</a></li>
            @endcan
            {{-- @can('read_product')
              <li><a href="{{ route('admin.products.index') }}" class="slide-item">محصولات</a></li>
            @endcan --}}
          </ul>
        </li>
      @endcanany

    </ul>
  </div>
</aside>