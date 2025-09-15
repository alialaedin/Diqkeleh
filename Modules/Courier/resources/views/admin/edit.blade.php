<x-layouts.master title="پیک ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="پیک ها" :route="route('admin.couriers.index')" />
      <x-breadcrumb-item title="ویرایش پیک" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش پیک">
    <x-form :action="route('admin.couriers.update', $courier)" method="PATCH">

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام" />
            <x-input type="text" name="first_name" required autofocus :default-value="$courier->first_name" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام خانوادگی" />
            <x-input type="text" name="last_name" required :default-value="$courier->last_name" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره همراه" />
            <x-input type="text" name="mobile" required :default-value="$courier->mobile" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تلفن ثابت" />
            <x-input type="text" name="telephone" :default-value="$courier->telephone" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="کد ملی" />
            <x-input type="text" name="national_code" :default-value="$courier->national_code" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب نوع پیک" />
            <x-select name="type" :data="$types" option-value="name" option-label="label" :default-value="$courier->type" />
          </x-form-group>
        </x-col>

        <x-col>
          <x-form-group>
            <x-label :is-required="true" text="آدرس" />
            <x-textarea name="address" :rows="2" :default-value="$courier->address" />
          </x-form-group>
        </x-col>

      </x-row>

    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>