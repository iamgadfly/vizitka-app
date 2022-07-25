<?php

namespace Database\Seeders;

use App\Models\MaintenanceSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceSettingsSeeder extends Seeder
{
    const NUMBER_TO_CREATE = 51; // cause of 51 specialist
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, self::NUMBER_TO_CREATE) as $i) {
            MaintenanceSettings::factory()->create();
        }
    }
}
