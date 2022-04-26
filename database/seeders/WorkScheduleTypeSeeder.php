<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkScheduleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('work_schedule_types')->insert([
            [
                'name' => 'sliding'
            ],
            [
                'name' => 'flexible'
            ],
            [
                'name' => 'standard'
            ]
        ]);
    }
}
