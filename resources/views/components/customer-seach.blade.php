@props([
  'name' => 'customer_id',
  'id' => 'customer-search-select'
])

<select {{ $attributes->merge(['class' => 'form-control fs-12', 'id' => $id, 'name' => $name]) }}>
	<option value=""></option>
</select>
@error($name)
  <span class="text-danger mt-2 fs-10">{{ $message }}</span>
@enderror

@push('CustomerSearchScripts')
	<script>
		$('#' + @json($id)).select2({
			ajax: {
				url: @json(route('admin.customers.search')),
				dataType: 'json',
				processResults: (response) => {
					let customers = response.data.customers || [];
					return {
						results: customers.map(customer => ({
							id: customer.id,
							mobile: customer.mobile,
							name: customer.full_name || ''
						})),
					};
				},
				cache: true,
			},
			placeholder: 'انتخاب مشتری',
			templateResult: (repo) => {

				if (repo.loading) {
					return "در حال بارگذاری...";
				}

				var $container = $(
					"<div class='select2-result-repository clearfix'>" +
					"<div class='select2-result-repository__meta'>" +
					"<div class='select2-result-repository__title'></div>" +
					"</div>" +
					"</div>"
				);

				let text = `موبایل: ${repo.mobile}`;
				if (repo.name) {
					text += ` | ${repo.name}`;
				}
				$container.find(".select2-result-repository__title").text(text);

				return $container;
			},
			minimumInputLength: 1,
			language: {
				inputTooShort: (args) => `حداقل ${args.minimum} حرف وارد کنید`,
				noResults: () => 'مشتری ای یافت نشد !',
			},
			templateSelection: (repo) => {
				let text = `موبایل: ${repo.mobile}`;
				if (repo.name) {
					text += ` | ${repo.name}`;
				}
				return repo.id ? text : repo.text;
			}
		});

	</script>
@endpush