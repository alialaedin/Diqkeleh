@props(['id', 'name', 'defaultValue' => null])

@php
	$textInputId = $id . '-show';
	$dateInputId = $id . '-hide';
	$oldVal = old($name, request($name, $defaultValue));
@endphp

<input class="form-control fc-datepicker fs-12" id="{{ $textInputId }}" type="text" placeholder="انتخاب تاریخ" />
<input 
	name="{{ $name }}" 
	id="{{ $dateInputId }}" 
	hidden 
	value="{{ $oldVal }}"
/>

@push('timeInputScriptStack')
	<script>

		var textInputSelector = '#' + @json($textInputId);
		var dateInputSelector = '#' + @json($dateInputId);

		$(textInputSelector).MdPersianDateTimePicker({
			targetDateSelector: dateInputSelector,
			targetTextSelector: textInputSelector,
			englishNumber: false,
			enableTimePicker: true,
      enableDatePicker: false,
			dateFormat: 'HH:mm',
			textFormat: 'HH:mm',
			groupId: 'rangeSelector1',
		});
	</script>
@endpush
