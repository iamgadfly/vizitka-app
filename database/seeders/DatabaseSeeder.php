<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            ActivityKindSeeder::class,
            OnboardingSeeder::class,
            ReportSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            SpecialistSeeder::class,
            BusinessCardSeeder::class,
            MaintenanceSettingsSeeder::class,
            MaintenanceSeeder::class,
            WorkScheduleSettingsSeeder::class,
            WorkScheduleWorkSeeder::class,
            WorkScheduleBreakSeeder::class
        ]);
    }
}
