<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Setting\Enums\SettingType;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->string('label');
      $table->string('name')->unique();
      $table->enum('type', SettingType::cases())->default(SettingType::TEXT);
      $table->json('options')->nullable();
      $table->longText('value')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('settings');
  }
};
