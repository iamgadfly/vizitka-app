<?php

namespace Database\Seeders;

use App\Models\WorkScheduleSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkScheduleSettingsSeeder extends Seeder
{
    const NUMBER_TO_CREATE = 51;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0 , self::NUMBER_TO_CREATE) as $i) {
            WorkScheduleSettings::factory()->create();
        }
    }
}
