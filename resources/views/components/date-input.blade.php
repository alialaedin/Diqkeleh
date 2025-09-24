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

@push('dateInputScriptStack')
	<script>

		var textInputSelector = '#' + @json($textInputId);
		var dateInputSelector = '#' + @json($dateInputId);

		$(textInputSelector).MdPersianDateTimePicker({
			targetDateSelector: dateInputSelector,
			targetTextSelector: textInputSelector,
			englishNumber: false,
			toDate: true,
			// enableTimePicker: true,
			dateFormat: 'yyyy-MM-dd',
			textFormat: 'yyyy-MM-dd',
			groupId: 'rangeSelector1',
		});
	</script>
@endpush
