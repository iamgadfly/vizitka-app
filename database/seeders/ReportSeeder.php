<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('reports')->insert([
            [
                'name' => 'offence'
            ],
            [
                'name' => 'wrong_description'
            ],
            [
                'name' => 'violence'
            ],
            [
                'name' => 'sexual_content'
            ],
            [
                'name' => 'other'
            ]
        ]);
    }
}
