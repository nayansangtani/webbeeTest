<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypesSeeder extends Seeder
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
                'name' => 'Hair cut',
            ],
            [
                'name' => 'Hair coloring',
            ],
            [
                'name' => 'Hair Styling',
            ],
        ];

        ServiceType::insert($days);
    }
}
