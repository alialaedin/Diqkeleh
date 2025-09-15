<x-layouts.master title="حساب های بانکی">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="حساب های بانکی" :route="route('admin.accounts.index')" />
      <x-breadcrumb-item title="ویرایش حساب بانکی" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش حساب بانکی">
    <x-form :action="route('admin.accounts.update', $account)" method="PATCH">
      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب کارمند" />
            <x-select name="employee_id" :data="$employees" option-value="id" option-label="full_name" :default-value="$account->employee_id" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره کارت" />
            <x-input type="text" name="card_number" :default-value="$account->card_number" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره شبا" />
            <x-input type="text" name="sheba_number" :default-value="$account->sheba_number" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام بانک" />
            <x-input type="text" name="bank_name" :default-value="$account->bank_name" required />
          </x-form-group>
        </x-col>
      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>