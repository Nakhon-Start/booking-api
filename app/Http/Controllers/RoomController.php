<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Responsibilities;
use Illuminate\Http\Request;
use App\Models\Room;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{

    public function CreateRoom(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:64',
            'description' => 'required|string|max:255',
            'building_id' => 'required|integer'
        ]);


        $is_checker = Responsibilities::where('user_id', '=', Auth::user()->id)
            ->where('building_id', '=', $request['building_id'])
            ->first();


        if ($is_checker == null)
            return throw new Exception('Unauthenticated.');

        $room = Room::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'building_id' => $request['building_id'],
            'create_by' => Auth::user()->id
        ]);

        return response()->json([
            'Room' => $room
        ], 200);
    }

    public function GetRoom($id)
    {

        $data = Room::find($id);

        if ($data) {
            return response()->json([
                'message' => 'view Room Success !',
                'user' => $data
            ], 200);
        } else {
            return response()->json(['message' => 'Not view Room !'], 404);
        }
    }

    public function GetListRoom()
    {
        $data = Room::with(['building'])->get();

        if ($data) {
            return response()->json([
                'message' => 'view Room Success !', 'data' => $data
            ], 200);
        } else {
            return response()->json(['message' => 'Not view Room !'], 404);
        }
    }


    public function SetRoom(Request $request)
    {

        $is_checker = Room::find($request['id']);
        if ($is_checker == null)
            throw new Exception('invalid setRoom');

        //Todo: ตรวจสอบสิทธิ์ ของ checker
        if ($this->is_checker(array($is_checker->create_by), Auth::user()->id) == 0)
            throw new Exception('Unauthenticated.');


        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|string|max:64|unique:building',
            'description' => 'required|string|max:255',
            'is_active' => 'required|boolean',

        ]);


        $is_checker->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'is_active' => $request['is_active'],
            'create_by' => Auth::user()->id

        ]);

        return response()->json([

            'Room' => $is_checker,
        ]);
    }

    public function is_checker($building_id, $user_id)
    {
        $resp = Responsibilities::whereIn('building_id',  $building_id)
            ->where('user_id', '=', $user_id)
            ->get();
        return count($resp) > 0;
    }


    public function GetRoomListBooking(Request $request)
    {

        $request->validate([

            'room_id' => 'required|integer',

        ]);

        return Booking::with('room')
            ->where('room_id', '=', $request['room_id'])
            ->select('id','booker_id', 'start_date', 'end_date', 'booking_status' , 'room_id')
            ->get();
    }


    public function GetRoomListBookingByDate(Request $request)
    {

        $request->validate([

            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"',
            'booking_status' => 'required|int|min:-2|max:1'


        ]);


        return Booking::with('room')
            ->where('start_date', '>=', $request['start_date'])
            ->where('end_date', '<=', $request['end_date'])
            ->where('booking_status', '=', $request['booking_status'])
            ->select('id', 'booker_id', 'start_date', 'end_date', 'booking_status' , 'room_id')
            ->get();


    }
}
