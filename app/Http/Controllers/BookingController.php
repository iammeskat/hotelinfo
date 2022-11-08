<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HotelGuest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $hotel_info = $request['hotel_info']; // from middleware
        $bookings = Booking::with('guests')->where('hotel_id', $hotel_info->id)->get();
        return response()->json([
            'data' => [
                'bookings' => $bookings,
            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }

    /**
     * create booking with guests
     * @return json
     */
    public function create(Request $request)
    {
        // if (!array_key_exists('0', $request->all())) {
        //     return response()->json([
        //         'message' => 'Provide guests information',
        //         'error' => true,
        //     ], 200);
        // }
        $validator = Validator::make($request->all(), [
            'reservation_no' => 'required',
            'booking_source' => 'required',
            'arrival_date'  => 'required',
            'departure_date' => 'required',
            'guests.*.first_name' => 'required',
            'guests.*.last_name' => 'required',
            'guests.*.phone' => 'required',
            'guests.*.nid_no' => 'numeric',
            // 'guests.*.passport' => 'required',
            'guests.*.email' => 'email',
            'guests.*.dob' => 'required',
            'guests.*.nationality' => 'required',
            'guests.*.place_of_birth' => 'required',
            'guests.*.length_of_stay' => 'required',
            'guests.*.occupation' => 'required',
            'guests.*.room_id' => 'required|exists:rooms,id',
            'guests.*.image' => 'required',
            'guests.*.nid' => 'required',

        ]);
        // return $request->all();

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

        // create booking
        $booking = Booking::create([
            'hotel_id' => $hotel_info->id,
            'reservation_no' => $request['reservation_no'],
            'booking_source' => $request['booking_source'],
            'arrival_date'  => $request['arrival_date'],
            'departure_date' => $request['departure_date'],
        ]);

        $requestedGuests = $request['guests'];
        // unset($requestedGuests['user_info']);
        // unset($requestedGuests['hotel_info']);
        $guests = [];
        foreach ($requestedGuests as $guestData) {

            $img =  $this->uploadImage($guestData['image']);

            $nid =  $this->uploadImage($guestData['nid']);
            $data = [
                'first_name' => $guestData['first_name'],
                'last_name' => $guestData['last_name'],
                'hotel_id' => $hotel_info['id'],
                'booking_id' => $booking['id'],
                'phone' => $guestData['phone'],
                'nid_no' => $guestData['nid_no'],
                'email' => strtolower(trim($guestData['email'])),
                'dob' =>  $guestData['dob'],
                'nationality' => $guestData['nationality'],
                'place_of_birth' => $guestData['place_of_birth'],
                'arrival_date' =>  $request['arrival_date'],
                'leaving_date' =>  $request['departure_date'],
                'length_of_stay' => $guestData['length_of_stay'],
                'room_id' => $guestData['room_id'],
                'occupation' => $guestData['occupation'],
                'other_info' => $guestData['other_info'],
                'guest_img_path' => $img,
                'nid_img_path' => $nid,
                'status' => $guestData['status'],
            ];

            $guest = HotelGuest::create($data);
            array_push($guests, $guest);
        }

        // update room status
        foreach ($guests as $guest) {
            $room = Room::find($guest['room_id']);
            $room->update([
                "status" => 1,
            ]);
        }


        return response()->json([
            'data' => [
                'booking' => $booking,
                'guests' => $guests,

            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
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
