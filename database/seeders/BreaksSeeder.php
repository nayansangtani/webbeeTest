<?php

namespace Database\Seeders;

use App\Models\Breaks;
use Illuminate\Database\Seeder;

class BreaksSeeder extends Seeder
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
                'name' => 'Lunch',
                'start_time' => '12:00:00',
                'end_time' => '13:00:00',
                'is_recurring_every_slot' => 0,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Cleaning',
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'is_recurring_every_slot' => 0,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Cleaning After Every Slot',
                'start_time' => NULL,
                'end_time' => NULL,
                'is_recurring_every_slot' => 1,
                'duration_minutes' => 5
            ],
        ];

        Breaks::insert($days);
    }
}
