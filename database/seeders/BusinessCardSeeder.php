<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_cards')->insert([
           [
               'specialist_id' => 1,
               'title' => 'Backend developer',
               'about' => 'PHP/Laravel backend developer',
               'address' => 'Новороссийск, ул. Куникова 52',
               'placement' => '93',
               'floor' => '9',
               'background_image' => 'images/card_backgrounds/neutral_man_2.svg',
               'created_at' => date('Y-m-d H:i:s'),
               'updated_at' => date('Y-m-d H:i:s')
           ]
        ]);
    }
}
