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

			<li class="side-item side-item-category mt-0 mb-3 px-2">
				{{ auth()->user()->name . ' - ' . auth()->user()->role->label }}
			</li>

			<li class="slide">
				<a class="side-menu__item" href="{{route("admin.dashboard")}}">
					<i class="ti-dashboard sidemenu_icon"></i>
					<span class="side-menu__label">داشبورد</span>
				</a>
			</li>

			@canany(['read_addresses', 'read_units'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-bookmark sidemenu_icon"></i>
						<span class="side-menu__label">اطلاعات پایه</span>
						<i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@role('super_admin')
						<li><a href="{{ route('admin.roles.index') }}" class="slide-item"><span>نقش و مجوز ها</span></a></li>
						@endrole
						@can('read_ranges')
							<li><a href="{{ route('admin.ranges.index') }}" class="slide-item"><span>محدوده ها</span></a></li>
						@endcan
						@can('read_addresses')
							<li><a href="{{ route('admin.addresses.index') }}" class="slide-item"><span>آدرس مشتریان</span></a></li>
						@endcan
						@can('read_units')
							<li><a href="{{ route('admin.units.index') }}" class="slide-item"><span>واحد ها</span></a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@canany(['read_employees', 'read_salaries', 'read_accounts'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="fe fe-users sidemenu_icon"></i>
						<span class="side-menu__label">مدیریت پرسنل</span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@can('read_employees')
							<li><a href="{{ route('admin.employees.index')}}" class="slide-item">کارمندان</a></li>
						@endcan
						@can('read_salaries')
							<li><a href="{{ route('admin.salaries.index')}}" class="slide-item">حقوق های پرداخت شده</a></li>
						@endcan
						@can('read_accounts')
							<li><a href="{{ route('admin.accounts.index')}}" class="slide-item">حساب های بانکی</a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@can('read_couriers')
				<li class="slide">
					<a class="side-menu__item" href="{{route("admin.couriers.index")}}">
						<i class="ti-car sidemenu_icon"></i>
						<span class="side-menu__label">مدیریت پیک ها</span>
					</a>
				</li>
			@endcan

			@canany(['read_customers'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-user sidemenu_icon"></i>
						<span class="side-menu__label">مدیریت کاربران</span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@role('super_admin')
						<li><a href="{{ route('admin.admins.index') }}" class="slide-item"><span>ادمین ها</span></a></li>
						@endrole
						@can('read_customers')
							<li><a href="{{ route('admin.customers.index')}}" class="slide-item">مشتریان</a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@canany(['read_products', 'read_categories'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-package sidemenu_icon"></i>
						<span class="side-menu__label">محصولات</span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@can('read_categories')
							<li><a href="{{ route('admin.categories.index')}}" class="slide-item">دسته بندی ها</a></li>
						@endcan
						@can('read_products')
							<li><a href="{{ route('admin.products.index') }}" class="slide-item">محصولات</a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@can('read_orders')
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-shopping-cart sidemenu_icon"></i>
						<span class="side-menu__label">سفارشات</span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						<li><a href="{{ route('admin.orders.today-orders') }}" class="slide-item">سفارشات امروز</a></li>
						<li><a href="{{ route('admin.orders.index')}}" class="slide-item">همه سفارشات</a></li>
					</ul>
				</li>
			@endcan

			@can('read_payments')
				<li class="slide">
					<a class="side-menu__item" href="{{route("admin.payments.index")}}">
						<i class="ti-money sidemenu_icon"></i>
						<span class="side-menu__label">پرداختی ها</span>
					</a>
				</li>
			@endcan

			@canany(['read_stores', 'read_storeTransactions'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-server sidemenu_icon"></i>
						<span class="side-menu__label">مدیریت انبار</span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@can('read_stores')
							<li><a href="{{ route('admin.stores.index')}}" class="slide-item">موجودی محصولات</a></li>
							<li><a href="{{ route('admin.store-multi-charge.index')}}" class="slide-item">شارژ گروهی محصولات</a></li>
						@endcan
						@can('read_storeTransactions')
							<li><a href="{{ route('admin.store-transactions.index') }}" class="slide-item">تراکنش های انبار</a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@can('read_walletTransactions')
				<li class="slide">
					<a class="side-menu__item" href="{{route("admin.wallet-transactions.index")}}">
						<i class="ti-wallet sidemenu_icon"></i>
						<span class="side-menu__label">کیف پول</span>
					</a>
				</li>
			@endcan

			@role('super_admin')
			<li class="slide">
				<a class="side-menu__item" data-toggle="slide" href="#">
					<i class="ti-bar-chart sidemenu_icon"></i>
					<span class="side-menu__label">گزارشات</span><i class="angle fa fa-angle-left"></i>
				</a>
				<ul class="slide-menu">
					<li><a href="{{ route('admin.reports.customers')}}" class="slide-item">مشتریان</a></li>
					<li><a href="{{ route('admin.reports.products')}}" class="slide-item">محصولات</a></li>
					<li><a href="{{ route('admin.reports.today-sales')}}" class="slide-item">فروش امروز</a></li>
				</ul>
			</li>
			@endrole

			@canany(['read_headlines', 'read_revenues', 'read_expenses'])
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="#">
						<i class="ti-credit-card sidemenu_icon"></i>
						<span class="side-menu__label">مدیریت هزینه و درامد </span><i class="angle fa fa-angle-left"></i>
					</a>
					<ul class="slide-menu">
						@can('read_headlines')
							<li><a href="{{route("admin.headlines.index")}}" class="slide-item">سرفصل ها</a></li>
						@endcan
						@can('read_expenses')
							<li><a href="{{route("admin.expenses.index")}}" class="slide-item">هزینه ها</a></li>
						@endcan
						@can('read_revenues')
							<li><a href="{{route("admin.revenues.index")}}" class="slide-item">درامد ها</a></li>
						@endcan
					</ul>
				</li>
			@endcanany

			@role('super_admin')
			<li class="slide">
				<a class="side-menu__item" href="{{route("admin.settlement.index")}}">
					<i class="ti-clipboard sidemenu_icon"></i>
					<span class="side-menu__label">تسویه با پیک</span>
				</a>
			</li>
			<li class="slide">
				<a class="side-menu__item" href="{{route("admin.settings.index")}}">
					<i class="ti-settings sidemenu_icon"></i>
					<span class="side-menu__label">تنظیمات</span>
				</a>
			</li>
			<li class="slide">
				<a class="side-menu__item" href="{{route("admin.activities.index")}}">
					<i class="ti-pulse sidemenu_icon"></i>
					<span class=" side-menu__label">لاگ فعالیت ها</span>
				</a>
			</li>
			@endrole
		</ul>
	</div>
</aside>