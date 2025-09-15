<x-layouts.master title="تنظیمات">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="تنظیمات" />
		</x-breadcrumb>
	</div>

	<x-card title="تنظیمات">
		<x-form :action="route('admin.settings.update')" method="PATCH">

			@php
				$primitiveTypes = [
					\Modules\Setting\Enums\SettingType::TEXT->value,
					\Modules\Setting\Enums\SettingType::NUMBER->value,
					\Modules\Setting\Enums\SettingType::EMAIL->value,
				];
			@endphp

			@foreach ($primitiveTypes as $primitiveType)
				@if ($settingTypes->has($primitiveType))
					<x-row>
						@foreach ($settingTypes[$primitiveType] as $setting)
							<x-col md="6" lg="3" xl="3">
								<x-form-group>
									<x-label :text="$setting->label" :for="$setting->name" class="font-weight-bold" />
									<x-input :type="$primitiveType" :name="$setting->name" :default-value="$setting->value" />
								</x-form-group>
							</x-col>
						@endforeach
					</x-row>
				@endif
			@endforeach

			@if ($settingTypes->has(\Modules\Setting\Enums\SettingType::IMAGE->value))
				<x-row>
					@foreach ($settingTypes[\Modules\Setting\Enums\SettingType::IMAGE->value] as $setting)
						<x-col md="6" lg="3" xl="3">
							<x-form-group>
								<x-label :text="$setting->label" :for="$setting->name" class="font-weight-bold" />
								<x-input type="file" :name="$setting->name" />
							</x-form-group>
						</x-col>
						@if ($setting->file)
							<x-col md="6" lg="3" xl="3">
								<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('delete-image-{{ $setting->id }}')">
									<i class="fe fe-trash"></i>
								</button>
								<x-delete-button :model="$setting" route="admin.settings.delete-file" />
								<br>
								<figure class="figure">
									<img src="{{ $setting->file['url'] }}" class="img-thumbnail" width="50" height="50" />
								</figure>
							</x-col>
						@endif
					@endforeach
				</x-row>
			@endif

			@if ($settingTypes->has(\Modules\Setting\Enums\SettingType::TEXTAREA->value))
				<x-row>
					@foreach ($settingTypes[\Modules\Setting\Enums\SettingType::TEXTAREA->value] as $setting)
						<x-col md="6" lg="3" xl="3">
							<x-form-group>
								<x-label :text="$setting->label" :for="$setting->name" class="font-weight-bold" />
								<x-textarea :name="$setting->name" :default-value="$setting->value" />
							</x-form-group>
						</x-col>
					@endforeach
				</x-row>
			@endif

			@if ($settingTypes->has(\Modules\Setting\Enums\SettingType::BOOLEAN->value))
				<x-row>
					@foreach ($settingTypes[\Modules\Setting\Enums\SettingType::BOOLEAN->value] as $setting)
						<x-col md="6" lg="3" xl="3">
							<x-form-group>
								<x-checkbox :name="$setting->name" :title="$setting->label" :is-checked="$setting->value" />
							</x-form-group>
						</x-col>
					@endforeach
				</x-row>
			@endif

			@if ($settingTypes->has(\Modules\Setting\Enums\SettingType::DATE->value))
				<x-row>
					@foreach ($settingTypes[\Modules\Setting\Enums\SettingType::DATE->value] as $setting)
						<x-col md="6" lg="3" xl="3">
							<x-form-group>
								<x-label :text="$setting->label" :for="$setting->name" class="font-weight-bold" />
								<x-date-input :id="$setting->name" :name="$setting->name" :default-value="$setting->value" />
							</x-form-group>
						</x-col>
					@endforeach
				</x-row>
			@endif

			@if ($settingTypes->has(\Modules\Setting\Enums\SettingType::TIME->value))
				<x-row>
					@foreach ($settingTypes[\Modules\Setting\Enums\SettingType::TIME->value] as $setting)
						<x-col md="6" lg="3" xl="3">
							<x-form-group>
								<x-label :text="$setting->label" :for="$setting->name" class="font-weight-bold" />
								<x-time-input :id="$setting->name" :name="$setting->name" :default-value="$setting->value" />
							</x-form-group>
						</x-col>
					@endforeach
				</x-row>
			@endif

		</x-form>
	</x-card>

	@foreach ($settingTypes as $type => $settings)
		@if ($type == \Modules\Setting\Enums\SettingType::IMAGE->value)
			@foreach ($settings as $setting)
				<form action="{{ route('admin.settings.delete-file', $setting) }}"
					id="delete-image-{{ $setting->id }}" method="POST" class="d-none">
					@csrf
					@method('DELETE')
				</form>
			@endforeach
		@endif
	@endforeach

	@push('scripts')
		@stack('dateInputScriptStack')
		@stack('timeInputScriptStack')
	@endpush

</x-layouts.master>