<?php

namespace Database\Seeders;

use App\Models\WorkScheduleBreak;
use App\Models\WorkScheduleDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkScheduleBreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalDays = WorkScheduleDay::all()->count() * 2;
        foreach (range(0, $totalDays - 1) as $i) {
            WorkScheduleBreak::factory()->create();
        }
    }
}
