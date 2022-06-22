<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Slot;
use App\Models\Breaks;
use App\Models\Holiday;
use App\Models\SlotBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class SlotBookingController extends Controller
{
    public function bookSlot(Request $request)
    {
        try {
            $this->validate($request, SlotBooking::rules());

            $slot = Slot::whereDate('date', $request->get('date'))
                ->where('start_time', $request->get('start_time'))
                ->first();

            //check if slot exist for the selected time and date or if it is fully booked.  
            if (!$slot) {
                return errorReponse("Invalid Slot.", 422);
            } elseif ($slot->is_fully_booked) {
                return errorReponse("The selected slot is fully booked.", 422);
            } elseif ( $request->get('customer_gender') == Slot::GENDER_MALE && $slot->available_male_seats == 0) {
                return errorReponse("The slot for males are fully booked.", 422);
            } elseif ( $request->get('customer_gender') == Slot::GENDER_FEMALE && $slot->available_female_seats == 0) {
                return errorReponse("The slot for females are fully booked.", 422);
            }

            $reqParams = $request->all();
            $reqParams['slot_id'] = $slot->id;

            //using db transaction as there are more than on trasaction to database (to rollback in case of any unexpected error)
            DB::beginTransaction();
            $response = SlotBooking::create($reqParams);

            $slot->booked_seats = $slot->booked_seats + 1;

            if ($reqParams['customer_gender'] == Slot::GENDER_MALE) {
                $slot->available_male_seats = $slot->available_male_seats - 1;
            } else {
                $slot->available_female_seats = $slot->available_female_seats - 1;
            }

            if ($slot->available_male_seats == 0 && $slot->available_female_seats == 0) {
                $slot->is_fully_booked = 1;
            }
            $slot->save();

            DB::commit();

            return successResponse($response, 201);
        } catch (ValidationException $error) {
            return errorReponse($error->errors(), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return errorReponse($e->getMessage());
        }
    }

    public function getBookings(Request $request)
    {
        try {
            $this->validate($request, ['date' => 'date_format:Y-m-d']);

            //if we dont get date in request then consider today's date
            if ($request->get('date')) {
                $date = $request->get('date');
                $dayOfWeek = Carbon::parse($request->get('date'))->dayOfWeek;
            } else {
                $dayOfWeek = Carbon::today()->dayOfWeek;
                $date = Carbon::today()->format('Y-m-d');
            }

            $holiday = Holiday::whereDate('date', $date)
                ->where('is_available', 0)
                ->first();

            //if holiday on a selected date then return error
            if ($holiday) {
                return errorReponse("Holiday on selected date.");
            }

            //check if slots are created for the date or not
            $bookingAvailable = Slot::whereDate('date', $date)->exists();
            if (!$bookingAvailable) {
                return errorReponse("Booking is not opened for the selected date.");
            }

            //getting booking start time and end time for perticular day
            $day = Day::where('value', $dayOfWeek)->first();
            $start_time = $day->start_time;
            $end_time = $day->end_time;

            //getting all break time configured in db
            $breaks = Breaks::where('is_recurring_every_slot', 0)->get();

            $bookings = Slot::select('sb.*')
                ->join('slot_bookings as sb', 'slots.id', 'sb.slot_id')
                ->whereDate('date', $date)
                ->get();

            $maleAvailableSeats = Slot::whereDate('date', $date)->sum('available_male_seats');
            $femaleAvailableSeats = Slot::whereDate('date', $date)->sum('available_female_seats');

            //Creating response
            $response['date'] = $date;
            $response['start_time'] = $start_time;
            $response['end_time'] = $end_time;
            $response['total_slot_time'] = 15;
            $response['actual_slot_time'] = 10;
            $response['break_between_slots'] = 5;
            $response['total_seats_available'] = $maleAvailableSeats + $femaleAvailableSeats;
            $response['total_male_seats_available'] = $maleAvailableSeats;
            $response['total_female_seats_available'] = $femaleAvailableSeats;
            $response['breaks'] = $breaks;
            $response['bookings'] = $bookings;

            return successResponse($response, 200);
        } catch (ValidationException $error) {
            return errorReponse($error->errors(), 422);
        } catch (\Exception $e) {
            return errorReponse($e->getMessage());
        }
    }
}
