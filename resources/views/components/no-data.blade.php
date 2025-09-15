@props([
	'colspan',
	'desc' => 'هیچ داده ای یافت نشد'
])

<tr>
	<td colspan="{{ $colspan }}">
		<div class="text-center">
			<span class="text-danger font-weight-bold">{{ $desc }}</span>
		</div>
	</td>
</tr>