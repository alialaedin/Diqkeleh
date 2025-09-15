<x-layouts.master title="کارمندان">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="کارمندان" :route="route('admin.employees.index')" />
      <x-breadcrumb-item title="ثبت کارمند جدید" />
    </x-breadcrumb>
  </div>

  <x-card title="کارمند جدید">
    <x-form :action="route('admin.employees.store')" method="POST">
      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام و نام خانوادگی" />
            <x-input type="text" name="full_name" required autofocus />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تلفن همراه" />
            <x-input type="text" name="mobile" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="کد ملی" />
            <x-input type="text" name="national_code" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="حقوق پایه (تومان)" />
            <x-input type="text" name="base_salary" class="comma" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تاریخ استخدام" />
            <x-date-input id="employmented_at" name="employmented_at" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تلفن ثابت" />
            <x-input type="text" name="telephone"  />
          </x-form-group>
        </x-col>
        <x-col>
          <x-form-group>
            <x-label :is-required="true" text="محل سکونت" />
            <x-textarea name="address" rows="3" />
          </x-form-group>
        </x-col>
      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('dateInputScriptStack')
  @endpush

</x-layouts.master>