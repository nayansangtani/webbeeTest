<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Null_;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [
            [
                'name' => 'sunday',
                'is_open' => 0,
                'start_time' => NULL,
                'end_time' => NULL,
                'value' => 0
            ],
            [
                'name' => 'monday',
                'is_open' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
                'value' => 1
            ],
            [
                'name' => 'tuesday',
                'is_open' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
                'value' => 2
            ],
            [
                'name' => 'wednesday',
                'is_open' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
                'value' => 3
            ],
            [
                'name' => 'thursday',
                'is_open' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
                'value' => 4
            ],
            [
                'name' => 'friday',
                'is_open' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
                'value' => 5
            ],
            [
                'name' => 'saturday',
                'is_open' => 1,
                'start_time' => '10:00',
                'end_time' => '22:00',
                'value' => 6
            ],
        ];

        Day::insert($days);
    }
}
