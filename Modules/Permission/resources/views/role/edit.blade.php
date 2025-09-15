<x-layouts.master title="نقش ها">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="نقش ها" :route="route('admin.roles.index')" />
      <x-breadcrumb-item title="ویرایش نقش" />
    </x-breadcrumb>
  </div>

  <x-card title="ویرایش نقش">
    <x-form :action="route('admin.roles.update', $role)" method="PATCH">

      <x-row>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="عنوان انگیلیسی" />
            <x-input type="text" name="name" :default-value="$role->name" />
          </x-form-group>
        </x-col>
        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label :is-required="true" text="نام فارسی" />
            <x-input type="text" name="label" :default-value="$role->label" />
          </x-form-group>
        </x-col>
      </x-row>

      <h4 class="header font-weight-bold text-center fs-20 p-2 mb-5">مجوزها</h4>
      @foreach($permissions->chunk(4) as $chunk)
        <x-row>
          @foreach ($chunk as $permission)
            <x-col lg="6" xl="3">
              <x-form-group class="mb-1">
                <x-checkbox 
                  name="permissions[]" 
                  :value="$permission->id" 
                  :title="$permission->label"
                  :is-checked="$role->permissions->contains($permission->id)"
                 />
              </x-form-group>
            </x-col>
          @endforeach
        </x-row>
      @endforeach

    </x-form>
  </x-card>

</x-layouts.master>