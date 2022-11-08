<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            '*.room_name' => 'required',
            '*.floor_no' => 'required',
            '*.number_of_bed' => 'required',
            '*.has_ac' => 'required|boolean',
            '*.room_type' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'inputs' => $request->input(),
                ],
                'errors' => $validator->errors()->all(),
                'message' => 'Validation Failed',
                'error' => true,

            ], 200);
        }


        $hotel_info = $request['hotel_info']; // from middleware

        $inputRooms  = $request->input();
        unset($inputRooms['hotel_info']);
        unset($inputRooms['user_info']);
        // return $inputRooms;
        $insertedRooms = [];
        foreach ($inputRooms as $iroom) {
            $room = Room::create([
                'hotel_id' => $hotel_info['id'],
                'room_name' => $iroom['room_name'],
                'floor_no' => $iroom['floor_no'],
                'number_of_bed' => $iroom['number_of_bed'],
                'has_ac' => $iroom['has_ac'],
                // 'other_info' =>  $iroom['other_info'],
                'room_type' => $iroom['room_type'],
            ]);
            array_push($insertedRooms, $room);
        }

        return response()->json([
            'data' => [
                'rooms' => $insertedRooms,
            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }
    public function show($id)
    {
        $room = Room::with('guests')->find($id);
        return response()->json([
            'data' => [
                'room' => $room,
            ],
            'message' => 'Successfull',
            'error' => false,
        ], 200);
    }
    public function index(Request $request)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $rooms = Room::where('hotel_id', $hotel_info->id)->get();
        return response()->json([
            'data' => [
                'rooms' => $rooms,
            ],
            'message' => 'Successfull',
            'error' => false,
        ], 200);
    }

    public function availableRoom(Request $request)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $rooms = Room::where('hotel_id', $hotel_info->id)->where('status', 0)->get();
        return response()->json([
            'data' => [
                'rooms' => $rooms,
            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }

    public function update(Request $request, $room_id)
    {

        $validator = Validator::make($request->all(), [
            'room_name' => 'required',
            'floor_no' => 'required',
            'number_of_bed' => 'required',
            'has_ac' => 'required|boolean',
            'room_type' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'inputs' => $request->input(),
                ],
                'errors' => $validator->errors()->all(),
                'message' => 'Validation Failed',
                'error' => true,

            ], 200);
        }


        $hotel_info = $request['hotel_info']; // from middleware
        $room = Room::where('id', $room_id)->where('hotel_id', $hotel_info['id'])->first();
        if ($room) {
            $room->update([
                'room_name' => $request['room_name'],
                'floor_no' => $request['floor_no'],
                'number_of_bed' => $request['number_of_bed'],
                'has_ac' => $request['has_ac'],
                'other_info' =>  $request['other_info'],
                'room_type' => $request['room_type'],
            ]);

            return response()->json([
                'data' => [
                    'room' => $room,
                ],
                'message' => 'Successfully updated',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Data not found',
            'error' => true,
        ], 200);
    }
    public function chngStatus(Request $request, $room_id)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $room = Room::where('id', $room_id)->where('hotel_id', $hotel_info['id'])->first();
        if ($room) {
            $room->update([
                'status' => $room['status'] === 0 ? 1 : 0,
            ]);

            return response()->json([
                'data' => [
                    'room' => $room,
                ],
                'message' => 'Status changed',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Data not found',
            'error' => true,
        ], 200);
    }
    public function destroy(Request $request, $room_id)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $room = Room::where('id', $room_id)->where('hotel_id', $hotel_info['id'])->first();
        if ($room) {
            $room->delete();

            return response()->json([
                'data' => [
                    'room' => $room,
                ],
                'message' => 'Successfully deleted',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Data not found',
            'error' => true,
        ], 200);
    }
}
