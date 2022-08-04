<?php

namespace Database\Seeders;

use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleWork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkScheduleWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalDays = WorkScheduleDay::count();
        foreach (range(0, $totalDays - 1) as $i) {
            WorkScheduleWork::factory()->create();
        }
    }
}
