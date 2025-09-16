<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Modules\Setting\Enums\SettingType;
use Modules\Setting\Models\Setting;

class SettingController extends Controller
{
	public function index()
	{
		$settingTypes = Setting::all()->groupBy('type');
		$types = SettingType::cases();

		return view('setting::index', compact(['settingTypes', 'types']));
	}

	public function update(Request $request)
	{
		$inputs = $request->except(['_token', '_method']);
		
		foreach ($inputs as $name => $value) {
			if ($setting = Setting::where('name', $name)->first()) {
				if (in_array($setting->type, SettingType::getFileTypes()) && $value->isValid()) {
					$setting->uploadFile($value);
					$setting->refresh();
					$value = $setting->file['url'];
				}
				if ($setting->type == SettingType::BOOLEAN) {
					$value = $value === 'on' ? 1 : 0;
				}
				if ($setting->type == SettingType::PRICE) {
					$value = (int) str_replace(',', '', $value);
				}
				$setting->update(['value' => $value]);
			}
		}

		Toastr::success('تنظیمات با موفقیت به روزرسانی شد');

		return redirect()->back();
	}

	public function deleteFile(Setting $setting): \Illuminate\Http\RedirectResponse
	{
		abort_if(!in_array($setting->type, SettingType::getFileTypes()), 403);

		$setting->deleteMedia($setting->file['id']);
		$setting->update(['value' => null]);

		Toastr::success('فایل با موفقیت حذف شد');

		return redirect()->back();
	}
}
