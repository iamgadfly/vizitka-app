<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specialists')->insert([
           [
               'user_id' => 1,
               'name' => 'Николай',
               'surname' => 'Семеновский',
               'activity_kind_id' => 8,
               'created_at' => date('Y-m-d H:i:s'),
               'updated_at' => date('Y-m-d H:i:s'),
           ]
        ]);
        Specialist::factory(50)->create();
    }
}
