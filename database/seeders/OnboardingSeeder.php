<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OnboardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('onboardings')->insert([
            [
                'title' => 'Онлайн запись клиентов',
                'description' => 'Создавайте, редактируйте и управляйте\n записями клиентов',
                'text_button' => 'Дальше',
                'image' => 'images/onboarding/onboarding_1.svg'
            ],
            [
                'title' => 'База клиентов и рассылка',
                'description' => 'Удобное храненине и работа с клиентской\n базой в смартфоне',
                'text_button' => 'Дальше',
                'image' => 'images/onboarding/onboarding_2.svg'
            ],
            [
                'title' => 'Анализ источника лидов',
                'description' => 'Подробная статистика источников лидов\n поможет при планировании бизнеса',
                'text_button' => 'Дальше',
                'image' => 'images/onboarding/onboarding_3.svg'
            ],
            [
                'title' => 'Реферальная программа',
                'description' => 'Приглашайте друзей по своей реферальной\n ссылке и получайте бонусы',
                'text_button' => 'Все понятно',
                'image' => 'images/onboarding/onboarding_4.svg'
            ],
        ]);
    }
}
