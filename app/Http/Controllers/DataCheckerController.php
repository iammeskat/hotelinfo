<?php

namespace App\Http\Controllers;

use App\Models\HotelAuthority;
use App\Models\User;
use Illuminate\Http\Request;

class DataCheckerController extends Controller
{
    public function checkEmail($email)
    {
        $check = User::where('email', $email)->first();
        if ($check) {
            return response()->json([
                "isUsed" => true,
                "message" => "Email already in Use. You can't use this.",

            ]);
        }
        return response()->json([
            "isUsed" => false,
            "message" => "You can use this email.",
        ]);
    }

    public function checkPhone($phone)
    {
        $check = User::where('phone', $phone)->first();
        if ($check) {
            return response()->json([
                "isUsed" => true,
                "message" => "Phone already in Use. You can't use this.",

            ]);
        }
        return response()->json([
            "isUsed" => false,
            "message" => "You can use this phone.",

        ]);
    }
    public function checkNid($nid)
    {
        $check = HotelAuthority::where('nid', $nid)->first();
        if ($check) {
            return response()->json([
                "isUsed" => true,
                "message" => "This owner already has a user account. Please provide his/her email and phone. Or provide valid nid.",

            ]);
        }
        return response()->json([
            "isUsed" => false,
            "message" => "You can use this nid.",
        ]);
    }
}
