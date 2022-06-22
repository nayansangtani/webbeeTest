<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class Holidays extends Seeder
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
                'name' => 'Test Holiday',
                'date' => '2022-06-24',
                'is_available' => '0',
            ],
        ];

        Holiday::insert($days);
    }
}
