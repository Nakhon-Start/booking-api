<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Responsibilities;
use Illuminate\Http\Request;
use App\Models\Room;
use Exception;
use Illuminate\Support\Facades\Auth;


class RoomController extends Controller
{

    public function CreateRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:64',
            'description' => 'required|string|max:255',
            'building_id' => 'required|integer',
            'room_type' => 'required|string',
            'quantity' => 'required|integer'
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
            'room_type' => $request['room_type'],
            'quantity' => $request['quantity'],
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
            return response()->json(['message' => 'Room not found !'], 404);
        }
    }

    public function GetListRoom()
    {
        return Room::with(['building', 'user'])->latest('id')->get();
    }


    public function SetRoom(Request $request)
    {
        $is_checker = Room::find($request['id']);

        if ($is_checker == null)
            throw new Exception('invalid setRoom');

        //Todo: ตรวจสอบสิทธิ์ ของ checker
        if ($this->is_checker(array($is_checker->create_by), Auth::user()->id))
            throw new Exception('Unauthenticated.');


        $this->validate($request, [
            'id' => 'required|integer',
            'name' => 'required|string|max:64|unique:building',
            'description' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'room_type' => 'required|string',
            'quantity' => 'required|integer'
        ]);

        $is_checker->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'is_active' => $request['is_active'],
            'room_type' => $request['room_type'],
            'quantity' => $request['quantity'],
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
            ->select(
                'id',
                'booker_id',
                'start_date',
                'end_date',
                'booking_status',
                'room_id',
            )
            ->get();
    }
    public function SearchForRoomsByTime(Request $request)
    {
        $request->validate([

            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"',

        ]);

        $data = Booking::with('room')
            ->where('start_date', '>=', $request['start_date'])
            ->where('end_date', '<=', $request['end_date'])
            ->where('booking_status', '!=', '1')
            ->select(
                'id',
                'booker_note',
                'start_date',
                'end_date',
                'booking_status',
                'room_id',
            )
            ->get();


        return response()->json($data);
    }

    public function SearchForAvailableRoom(Request $request)
    {

        $request->validate([

            'room_id' => 'required|integer',
        ]);

        $data = Booking::with('room')
            ->where('room_id', '=', $request['room_id'])
            ->where('booking_status', '=', '1')
            ->select('id', 'booker_note', 'start_date', 'end_date', 'room_id')
            ->get();


        return response()->json($data);
    }

    public function GetRoomListBookingByDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date_format:"Y-m-d"',
            'end_date' => 'required|date_format:"Y-m-d"',
            'booking_status' => 'required|int|min:-2|max:1'
        ]);

        $data = Booking::with('room')
            ->where('start_date', '>=', $request['start_date'])
            ->where('end_date', '<=', $request['end_date'])
            ->where('booking_status', '=', $request['booking_status'])
            ->select('id', 'booker_id', 'start_date', 'end_date', 'booking_status', 'room_id')
            ->get();

        return response()->json(['data' => $data]);
    }
}
