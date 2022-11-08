<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\HotelGuest;
use App\Models\Hotel;
use App\Models\Room;
use Exception;
use Illuminate\Support\Facades\Validator;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GuestController extends Controller
{

    public function guestsByHotel(Request $request, $id = false)
    {
        if ($id == false) {
            $hotel_info = $request['hotel_info']; // from middleware
            $id = $hotel_info->id;
        }
        $guests = HotelGuest::with('booking', 'room')->where('hotel_id', $id)->get();

        return response()->json([
            "data" => [
                "guests" => $guests
            ],
            "error" => false,
        ], 200);
    }


    /**
     * create police station
     * @return json
     */
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|numeric',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'nid_no' => 'required|numeric',
            // 'passport_no' => 'required|numeric',
            'email' => 'email',
            'dob' => 'required',
            'nationality' => 'required',
            'place_of_birth' => 'required',
            'arrival_date' => 'required',
            'length_of_stay' => 'required',
            'occupation' => 'required',
            'room_id' => 'required',
            'image' => 'required',
            'nid' => 'required',

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


        $guestData = $request->input();

        $img =  $this->uploadImage($guestData['image']);

        $nid =  $this->uploadImage($guestData['nid']);
        $data = [
            'first_name' => $guestData['first_name'],
            'last_name' => $guestData['last_name'],
            'hotel_id' => $hotel_info['id'],
            'booking_id' => $guestData['booking_id'],
            'phone' => $guestData['phone'],
            'nid_no' => $guestData['nid_no'],
            'email' => strtolower(trim($guestData['email'])),
            'dob' =>  $guestData['dob'],
            'nationality' => $guestData['nationality'],
            'place_of_birth' => $guestData['place_of_birth'],
            'arrival_date' =>  $guestData['arrival_date'],
            'leaving_date' =>  $guestData['leaving_date'],
            'length_of_stay' => $guestData['length_of_stay'],
            'room_id' => $guestData['room_id'],
            'occupation' => $guestData['occupation'],
            'other_info' => $guestData['other_info'],
            'guest_img_path' => $img,
            'nid_img_path' => $nid,
            'status' => $guestData['status'],
        ];

        $guest = HotelGuest::create($data);



        return response()->json([
            'data' => [
                'guest' => $guest,

            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }
    /**
     * cupdate guest
     * @return json
     */
    public function update(Request $request, $guest_id)
    {

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|numeric',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'nid_no' => 'required|numeric',
            'email' => 'email|required',
            'dob' => 'required',
            'nationality' => 'required',
            'place_of_birth' => 'required',
            'arrival_date' => 'required',
            'length_of_stay' => 'required',
            'occupation' => 'required',
            'room_id' => 'required',

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

        $guestData = $request->input();

        $guest = HotelGuest::where('id', $guest_id)->where('hotel_id', $hotel_info['id'])->first();
        if ($guest) {

            $data = [
                'first_name' => $guestData['first_name'],
                'last_name' => $guestData['last_name'],
                'hotel_id' => $hotel_info['id'],
                'booking_id' => $guestData['booking_id'],
                'phone' => $guestData['phone'],
                'nid_no' => $guestData['nid_no'],
                'email' => strtolower(trim($guestData['email'])),
                'dob' =>  $guestData['dob'],
                'nationality' => $guestData['nationality'],
                'place_of_birth' => $guestData['place_of_birth'],
                'arrival_date' =>  $guestData['arrival_date'],
                'leaving_date' =>  $guestData['leaving_date'],
                'length_of_stay' => $guestData['length_of_stay'],
                'room_id' => $guestData['room_id'],
                'occupation' => $guestData['occupation'],
                'other_info' => $guestData['other_info'],
                'status' => $guestData['status'],
            ];

            $updatedData = $guest->update($data);

            return response()->json([
                'data' => [
                    'guest' => $updatedData,

                ],
                'message' => 'Successfully updated',
                'error' => false,
            ], 201);
        }
        return response()->json([

            'message' => 'Data not found',
            'error' => true,
        ], 201);
    }

    /**
     * delete guest
     * @return json
     */
    public function destroy(Request $request, $guest_id)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $guest = HotelGuest::where('id', $guest_id)->where('hotel_id', $hotel_info['id'])->first();
        if ($guest) {
            $updatedData = $guest->delete();
            return response()->json([
                'data' => [
                    'guest' => $updatedData,

                ],
                'message' => 'Successfully deleted',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Data not found',
            'error' => true,
        ], 201);
    }

    public function allGuests()
    {

        $guests = HotelGuest::with('room', 'hotel')->get();

        return response()->json([
            "data" => [
                "guests" => $guests
            ],
            "message" => "Successful",
            "error" => false,
        ], 200);
    }


    public function allPresentGuests()
    {
        $guests = HotelGuest::with('room', 'hotel')
            ->where('leaving_date', ">=", date("Y-m-d h:i:s"))->get();

        return response()->json([
            "data" => [
                "guests" => $guests
            ],
            "message" => "present guests",
            "error" => false,
        ], 200);
    }
    public function guestsByPoliceStation($id)
    {
        $hotels = Hotel::with('guests')->where('police_station_id', $id)->select('id')->get();
        $guests = [];
        foreach ($hotels as $hotel) {
            foreach ($hotel['guests'] as $guest) {
                array_push($guests, $guest);
            }
        }

        return response()->json([
            "data" => [
                "guests" => $guests
            ],
            "message" => "Successful",
            "error" => false,
        ], 200);
    }
    public function presentGuestsByPoliceStation($id)
    {
        $hotels = Hotel::with('guests')->where('police_station_id', $id)->select('id')->get();
        $guests = [];
        foreach ($hotels as $hotel) {
            foreach ($hotel['guests'] as $guest) {
                if ($guest['leaving_date'] >= date("Y-m-d h:i:s"))
                    array_push($guests, $guest);
            }
        }

        return response()->json([
            "data" => [
                "guests" => $guests
            ],
            "message" => "Successful",
            "error" => false,
        ], 200);
    }
    public function guestHistory($identification)
    {
        $history = HotelGuest::with('room', 'hotel', 'booking')
            ->where('nid_no', $identification)
            ->orWhere('phone', $identification)
            ->orWhere('email', $identification)
            ->select('id', 'booking_id', 'first_name', 'last_name', 'email', 'phone', 'nid_no', 'arrival_date', 'leaving_date', 'room_id', 'hotel_id', 'guest_img_path', 'nid_img_path')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json([
            "data" => [
                "history" => $history
            ],
            "message" => "Successful",
            "error" => false,
        ], 200);
    }


    public function uploadImage($imgString)
    {
        // try {
        $image_64 = $imgString; //your base64 encoded data

        // $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $extension = "bmp";

        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = date("Y-m-d-h-i-s") . Str::random(5) . '.' . $extension;

        Storage::disk('public')->put('images/' . $imageName, base64_decode($image));
        return 'images/' . $imageName;
    }
}
