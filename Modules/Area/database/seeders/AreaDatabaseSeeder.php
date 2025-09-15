<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;

class AreaDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvinceTableSeeder::class,
            CityTableSeeder::class,
        ]);
    }
}
