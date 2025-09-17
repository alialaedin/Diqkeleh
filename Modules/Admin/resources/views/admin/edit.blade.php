<x-layouts.master title="ادمین ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="ادمین ها" :route="route('admin.admins.index')" />
      <x-breadcrumb-item title="ویرایش ادمین" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش ادمین">
    <x-form :action="route('admin.admins.update', $admin)" method="PATCH">
      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام و نام خانوادگی" />
            <x-input type="text" name="name" :default-value="$admin->name" required autofocus />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام کاربری" />
            <x-input type="text" name="username" :default-value="$admin->username" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره موبایل" />
            <x-input type="text" name="mobile" :default-value="$admin->mobile" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب نقش" />
            <x-select name="role_id" :data="$roles" option-value="id" option-label="label"
              :default-value="$admin->role->id" />
          </x-form-group>
        </x-col>

      </x-row>

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="کلمه عبور" />
            <x-input type="password" name="password" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تکرار کلمه عبور" />
            <x-input type="password" name="password_confirmation" />
          </x-form-group>
        </x-col>

      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>