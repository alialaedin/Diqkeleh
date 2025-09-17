<x-layouts.master title="ادمین ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="ادمین ها" :route="route('admin.admins.index')" />
      <x-breadcrumb-item title="ثبت ادمین جدید" />
    </x-breadcrumb>
  </div>

  <x-card title="ادمین جدید">
    <x-form :action="route('admin.admins.store')" method="POST">
      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام و نام خانوادگی" />
            <x-input type="text" name="name" required autofocus />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام کاربری" />
            <x-input type="text" name="username" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره موبایل" />
            <x-input type="text" name="mobile" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب نقش" />
            <x-select name="role_id" :data="$roles" option-value="id" option-label="label" />
          </x-form-group>
        </x-col>

      </x-row>

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="کلمه عبور" />
            <x-input type="password" name="password" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تکرار کلمه عبور" />
            <x-input type="password" name="password_confirmation" required />
          </x-form-group>
        </x-col>

      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>