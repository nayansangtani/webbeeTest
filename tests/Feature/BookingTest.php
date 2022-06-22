<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetBookings()
    {
        $response = $this->get('/api/bookings');

        $response->assertStatus(200);
        $response->assertJsonPath('data.date', Carbon::now()->format('Y-m-d'));
    }

    public function testBookSlot()
    {
        $faker = \Faker\Factory::create();
        
        $email = $faker->email;
        $firstName = $faker->email;
        $lastName = $faker->email;

        $body = [
            'date' => '2022-06-22',
            'service_type_id' => '1',
            'start_time' => '16:30:00',
            'customer_email' => $email,
            'customer_first_name' => $firstName,
            'customer_last_name' => $lastName,
            'customer_gender' => 'male'
        ];

        $response = $this->post('/api/booking', $body);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.customer_email', $email);
        $response->assertJsonPath('data.customer_first_name', $firstName);
        $response->assertJsonPath('data.customer_last_name', $lastName);
    }
}
