<x-layouts.master title="حساب های بانکی">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="حساب های بانکی" :route="route('admin.accounts.index')" />
      <x-breadcrumb-item title="ثبت حساب بانکی جدید" />
    </x-breadcrumb>
  </div>

  <x-card title="حساب بانکی جدید">
    <x-form :action="route('admin.accounts.store')" method="POST">
      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب کارمند" />
            <x-select name="employee_id" :data="$employees" option-value="id" option-label="full_name" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره کارت" />
            <x-input type="text" name="card_number" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره شبا" />
            <x-input type="text" name="sheba_number" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام بانک" />
            <x-input type="text" name="bank_name" required />
          </x-form-group>
        </x-col>
      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>