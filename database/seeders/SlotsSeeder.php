<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Breaks;
use App\Models\Day;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class SlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //As per instructions currently creating slots for 7 day starting from today
        $period = CarbonPeriod::create(Carbon::now(), Carbon::now()->addDays(7));

        $days = Day::all();

        foreach ($period as $date) {
            $holiday = Holiday::whereDate('date', $date)->where('is_available', 0)->first();
            if (!$holiday) {
                $is_open = 0;

                foreach ($days as $day) {
                    if ($day->is_open && $date->dayOfWeek == $day->value) {
                        $is_open = 1;
                        $start_time = $day->start_time;
                        $end_time = $day->end_time;
                    }
                }

                if ($is_open) {
                    $this->createSlots($start_time, $end_time, $date);
                }
            }
        }
    }

    public function createSlots($start_time, $end_time, $date)
    {
        $breaks = Breaks::where('is_recurring_every_slot', 0)->get();

        $period = new CarbonPeriod($start_time, '15 minutes', $end_time);
        
        foreach ($period as $item) {
            $is_break_time = 0;
            //avoiding making slots between break times as configure in db
            foreach ($breaks as $break) {
                if ($item->format("H:i:s") >= $break->start_time && $item->format("H:i:s") < $break->end_time) {
                    $is_break_time = 1;
                }
            }

            if (!$is_break_time) {
                $slot['date'] = $date->format("Y-m-d");
                $slot['start_time'] = $item->format("H:i:s");
                $slot['end_time'] = $item->copy()->addMinutes(10)->format("H:i:s");
                $slot['max_customer'] = 3;
                $slot['available_male_seats'] = 3;
                $slot['available_female_seats'] = 3;
                Slot::create($slot);
            }
        }

        return true;
    }
}
