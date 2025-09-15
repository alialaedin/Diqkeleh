<x-layouts.master title="پیک ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="پیک ها" :route="route('admin.couriers.index')" />
      <x-breadcrumb-item title="ثبت پیک جدید" />
    </x-breadcrumb>
  </div>

  <x-card title="پیک جدید">
    <x-form :action="route('admin.couriers.store')" method="POST">

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام" />
            <x-input type="text" name="first_name" required autofocus />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام خانوادگی" />
            <x-input type="text" name="last_name" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="شماره همراه" />
            <x-input type="text" name="mobile" required />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تلفن ثابت" />
            <x-input type="text" name="telephone" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="کد ملی" />
            <x-input type="text" name="national_code" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="انتخاب نوع پیک" />
            <x-select name="type" :data="$types" option-value="name" option-label="label" />
          </x-form-group>
        </x-col>

        <x-col>
          <x-form-group>
            <x-label :is-required="true" text="آدرس" />
            <x-textarea name="address" :rows="2" />
          </x-form-group>
        </x-col>

      </x-row>

    </x-form>
  </x-card>

  @push('scripts')
    @stack('SelectComponentScripts')
  @endpush

</x-layouts.master>