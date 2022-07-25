<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialistSeeder extends Seeder
{
    const NUMBER_TO_CREATE = 50;
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
        foreach (range(0, self::NUMBER_TO_CREATE) as $i) {
            Specialist::factory()->create();
        }
    }
}
