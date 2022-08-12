<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    const NUMBER_TO_CREATE = 70;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
           [
               'user_id' => 1,
               'name' => 'Николай',
               'surname' => 'Карелин',
           ]
        ]);
        foreach (range(0, self::NUMBER_TO_CREATE) as $i) {
            Client::factory()->create();
        }
    }
}
