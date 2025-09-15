<?php

namespace Modules\Auth\Services\Admin;

use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ValidationException;

class AuthenticationService
{
  private Admin $admin;

  public function __construct(public Request $request) {}

  public function login()
  {
    $this->setAdmin();
    $this->checkPassword();
    $this->loginAdmin();
  }

  public function logout()
  {
    Auth::guard(Admin::GUARD_NAME)->logout();

		$this->request->session()->invalidate();
		$this->request->session()->regenerateToken();
  }

  private function setAdmin()
  {
    $this->admin = Admin::where('username', $this->request->input('username'))->first();
  }

  private function checkPassword()
  {
    if (!$this->admin || !Hash::check($this->request->input('password'), $this->admin->password)) {
      throw new ValidationException('نام کاربری یا رمز عبور نادرست است');
    }
  }

  private function loginAdmin()
  {
    $attempt = Auth::guard(Admin::GUARD_NAME)->attempt($this->request->validated(), 1);
    
    if (!$attempt) {
      throw new ValidationException('خطا در لاگین');
    } 

    $this->request->session()->regenerate();
    Toastr::success('کاربر با موفقیت وارد پنل شد');
  }
}
