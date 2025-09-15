<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Admin\Models\Admin;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('admins', function (Blueprint $table) {
			$table->id();
			$table->string('name')->nullable();
			$table->string('username', 20)->unique()->index();
			$table->string('email', 100)->unique()->nullable();
			$table->string('password');
			$table->string('mobile', 20)->unique()->nullable();
			$table->rememberToken();
			$table->timestamps();
		});

		self::createFirstAdmin();
	}

	public function down(): void
	{
		Schema::dropIfExists('admins');
	}

	private static function createFirstAdmin(): void
	{
		Admin::create([
			'name' => 'علی علاالدین',
			'username' => 'admin',
			'email' => 'admin@gmail.com',
			'password' => bcrypt(123456),
			'mobile' => '09368917169'
		]);
	}
};
