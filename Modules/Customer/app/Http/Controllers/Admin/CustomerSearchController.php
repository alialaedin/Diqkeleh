<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\Models\Customer;

class CustomerSearchController extends Controller
{
	public function search(Request $request)
	{
		$q = $request->input('q');

		$customers = Customer::query()
			->select(['id', 'full_name', 'mobile'])
			->where('full_name', 'LIKE', '%' . $q . '%')
			->when(is_numeric($q), function ($query) use ($q) {
				$query->orWhere('id', $q);
				$query->orWhere('mobile', 'LIKE', '%' . $q . '%');
			});

		$count = $customers->count();
		$customers = $customers->take(20)->get();

		return response()->success('', compact(['customers', 'count']));
	}

	public function searchForOrder(Request $request)
	{
		$request->validate([
			'mobile' => ['required', 'numeric', 'digits:11', 'starts_with:09']
		]);

		$isNew = false;
		$customer = Customer::query()->where('mobile', $request->mobile)->first();

		if (! $customer) {
			$isNew = true;
			$customer = Customer::create(['mobile' => $request->mobile]);
		}

		$customer->refresh()->load('addresses', 'wallet');

		return response()->success('مشتری', compact(['customer', 'isNew']));
	}
}
