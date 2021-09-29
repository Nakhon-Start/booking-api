<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Booking;
use App\Models\Building;
use App\Models\Responsibilities;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{


    public function Booking(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'booker_note' => 'required|string|max:255',
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"'
        ]);

        if ($request['start_date'] > $request['end_date']) {
            throw new Exception('Invalid date');
        }

        $booking = Booking::create([
            'booker_id' => Auth::user()->id,
            'room_id' => $request['room_id'],
            'booker_note' => $request['booker_note'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date']
        ]);

        return response()->json([
            'booking' => $booking,
        ], 200);
    }

    public function checkingHistory()
    {

        $user_id = Auth::user()->id;
    
        return Booking::with('room', 'user')->where('checker_id', '=', $user_id)->latest('id')->get();
    }


    public function bookerHistory()
    {
        $user_id = Auth::user()->id;
        $bookings = Booking::with('room', 'user', 'checker')
            ->where('booker_id', '=', $user_id)->latest('id')->get();

        return  $bookings;
    }

    public function Approve(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'accepted_note' => 'required|string|max:255',
            'rejected_note' => 'required|string|max:255'
        ]);


        $booking = Booking::find($request['booking_id']);
        if ($booking == null)
            throw new Exception('invalid booking_id');

        //Todo: ตรวจสอบสิทธิ์ ของ checker
        if ($this->is_checker(array($booking->room->building->id), Auth::user()->id) == 0)
            throw new Exception('Unauthenticated.');

        $booking->booking_status = 1;
        $booking->checker_note = $request['accepted_note'];
        $booking->checker_id = Auth::user()->id;
        $booking->save();
        //ปฏิเสธ booking ที่เหลือ ที่จองในวันระหว่าง start_date - end_date ของ $request['booking_id']

        $booking_rejected = Booking::where('id', '!=', $booking->id)
            ->where('room_id', '=', $booking->room_id)
            ->where(function ($query) use ($booking) {
                $query->WhereBetween('start_date', [$booking->start_date, $booking->end_date]);
                $query->orWhereBetween('end_date', [$booking->start_date, $booking->end_date]);
            });
        $booking_rejected->update(['booking_status' => 0, 'checker_id' => Auth::user()->id, 'checker_note' => $request['rejected_note']]);
        return response([
            'accepted' => $booking,
            'rejected' => $booking_rejected->get()
        ]);
    }

    public function is_checker($building_id, $user_id)
    {
        $resp = Responsibilities::whereIn('building_id',  $building_id)
            ->where('user_id', '=', $user_id)
            ->get();
        return count($resp) > 0;
    }

    public function ShowListBooking()
    {
        return Booking::with('room', 'user')->latest('id')->get();
    }

    public function ShowBooking($id)
    {

        $booking_id = Booking::find($id);

        if ($booking_id) {
            return response()->json([
                'message' => 'view Booking For you Success !',
                'user' => $booking_id
            ], 200);
        } else {
            return response()->json(['message' => 'Booking not found !'], 404);
        }
    }


    public function SetBooking(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'booker_note' => 'required|string|max:255',
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"'
        ]);

        if ($request['start_date'] > $request['end_date']) {
            throw new Exception('Invalid date');
        }

        $id = $request['id'];

        $setbooking = Booking::find($id);

        if ($setbooking->booker_id != Auth::user()->id) {
            throw new Exception('Unauthenticated.');
        }

        // -2=ยกเลิก /-1=รออนุมัต / 1=อนุมัต / 0=ไม่อนุมัต 

        $is_not_pending = $setbooking->booking_status != -1;
        if ($is_not_pending) {
            throw new Exception('Unauthenticated.');
        }

        $setbooking->update($request->all());

        return response()->json([
            'data' => $setbooking,
        ]);
    }

    public function Cancle(Request $request)
    {
        $request->validate([

            'id' => 'required',
            'booker_note' => 'required|string|max:255',

        ]);

        $id = $request['id'];

        $setbooking = Booking::find($id);

        if ($setbooking->booker_id != Auth::user()->id) {
            throw new Exception('Unauthenticated.');
        }

        // -2=ยกเลิก /-1=รออนุมัต / 1=อนุมัต / 0=ไม่อนุมัต 

        $is_not_pending = $setbooking->booking_status != -1;

        if ($is_not_pending) {
            throw new Exception('Not Cancle.');
        }

        $setbooking->booking_status = -2;
        $setbooking->save();


        $setbooking->update($request->all());


        return response()->json([
            'message' => 'Cancle booking Success',
            'Update by Checker' => Auth::user()->email,
            'data' => $setbooking,
        ]);
    }
}
