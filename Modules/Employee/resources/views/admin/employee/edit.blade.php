<x-layouts.master title="کارمندان">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="کارمندان" :route="route('admin.employees.index')" />
      <x-breadcrumb-item title="ویرایش کارمند" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش کارمند">
    <x-form :action="route('admin.employees.update', $employee)" method="PATCH">
      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام و نام خانوادگی" />
            <x-input type="text" name="full_name" required autofocus :default-value="$employee->full_name" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تلفن همراه" />
            <x-input type="text" name="mobile" required :default-value="$employee->mobile" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="کد ملی" />
            <x-input type="text" name="national_code" required :default-value="$employee->national_code" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="حقوق پایه (تومان)" />
            <x-input type="text" name="base_salary" class="comma" required :default-value="number_format($employee->base_salary)" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تاریخ استخدام" />
            <x-date-input id="employmented_at" name="employmented_at" required :default-value="$employee->employmented_at" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تلفن ثابت" />
            <x-input type="text" name="telephone" :default-value="$employee->telephone" />
          </x-form-group>
        </x-col>
        <x-col>
          <x-form-group>
            <x-label :is-required="true" text="محل سکونت" />
            <x-textarea name="address" rows="3" :default-value="$employee->address" />
          </x-form-group>
        </x-col>
      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('dateInputScriptStack')
  @endpush

</x-layouts.master>