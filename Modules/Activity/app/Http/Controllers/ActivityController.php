<?php

namespace Modules\Activity\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Activity\Models\Activity;

class ActivityController extends Controller
{
  public function index()
	{
		$activities = Activity::latest('id')->paginate(100);

		return view('activity::index', compact('activities'));
	}
}
