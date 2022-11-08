<?php

namespace App\Http\Controllers;

use App\Models\HotelAuthority;
use App\Models\Owner;
use App\Models\Hotel;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function ownerInfo()
    {
        $user = auth()->user();
        $authority = HotelAuthority::where('user_id', $user['id'])->first();
        $hotels = Owner::select('hotel_id')->where('owner_id', $authority['id'])->get();
        return [
            "user_id" => $user->id,
            "authority_id" => $authority->id,
            "email" => $user->email,
            "phone" => $user->phone,
            "name" => $authority->name,
            "address" => $authority->address,
            "nid" => $authority->nid,
            "political_identity" => $authority->political_identity,
            "position" => $authority->position,
            "hotels" => $hotels
        ];
    }
    public function profile()
    {
        return response()->json([
            "data" => [
                "info" => $this->ownerInfo(),
            ],
            "message" => "Successfull",
            "error" => false
        ]);
    }
    public function myHotels()
    {
        $ownerInfo = $this->ownerInfo();
        $hotels = array();
        foreach ($ownerInfo['hotels'] as $hotelInfo) {
            $hotel = Hotel::with('rooms', 'guests')->find($hotelInfo->hotel_id);
            array_push($hotels, $hotel);
        }
        return response()->json([
            "data" => [
                'hotels' => $hotels,
            ],
            "message" => "Successfull",
            "error" => false
        ]);
    }
}
