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
            ActivityKindSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            SpecialistSeeder::class,
            WorkScheduleSettingsSeeder::class,
            DeviceSeeder::class,
            BusinessCardSeeder::class,
            MaintenanceSettingsSeeder::class,
            MaintenanceSeeder::class,
            AdminUserSeeder::class,
            OnboardingSeeder::class,
            ReportSeeder::class,
            WorkScheduleWorkSeeder::class,
            WorkScheduleBreakSeeder::class,
        ]);
    }
}
