<x-layouts.master title="حقوق های پراخت شده">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="حقوق های پراخت شده" :route="route('admin.salaries.index')" />
      <x-breadcrumb-item title="ویرایش حقوق" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش حقوق">
    <x-form :action="route('admin.salaries.update', $salary)" method="PATCH">
      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب کارمند" />
            <x-select name="employee_id" :data="$employees" option-value="id" option-label="full_name" :default-value="$salary->employee_id" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="مبلغ (تومان)" />
            <x-input type="text" name="amount" class="comma" :default-value="number_format($salary->amount)" required />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="اضافه کاری (ساعت)" />
            <x-input type="number" name="overtime" :default-value="$salary->overtime" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="تاریخ پرداخت" />
            <x-date-input id="paid_at" name="paid_at" :default-value="$salary->paid_at" required />
          </x-form-group>
        </x-col>
        <x-col>
          <x-form-group>
            <x-label text="توضیحات" />
            <x-textarea name="description" rows="3" :default-value="$salary->description" />
          </x-form-group>
        </x-col>
      </x-row>
    </x-form>
  </x-card>

  @push('scripts')
    @stack('dateInputScriptStack')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>