<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelGuest;
use App\Models\PoliceStation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $data = null;
        if ($request->query('police')) {
            $data = PoliceStation::where('name', 'LIKE',  '%' . $request['police'] . '%')
                ->orWhere('district', 'LIKE',  '%' . $request['police'] . '%')
                ->get();
        } elseif ($request->query('hotel')) {
            $data = Hotel::where('name_en', 'LIKE',  '%' . $request['hotel'] . '%')
                ->orWhere('name_bn', 'LIKE',  '%' . $request['hotel'] . '%')
                ->get();
        } elseif ($request->query('guest')) {
            $keyword = $request['guest'];
            $data = HotelGuest::where(function ($query) use ($keyword) {
                $keyword = explode(" ", $keyword);
                $query->where(function ($query) use ($keyword) {
                    for ($i = 0; $i < count($keyword); $i++) {
                        $query->orwhere('first_name', 'LIKE',  '%' . $keyword[$i] . '%')
                            ->orwhere('last_name', 'LIKE',  '%' . $keyword[$i] . '%');
                    }
                })
                    ->orWhereIn('email', $keyword)
                    ->orWhereIn('phone', $keyword)
                    ->orWhereIn('nid_no', $keyword);
            })
                ->get();
        }

        if ($data != null) {
            return response()->json([
                'data' => [
                    'results' => $data
                ],
                'message' => "Search results",
                'error' => false
            ], 200);
        }

        return response()->json([

            'message' => "Invalid query key or query value is null. query key is like police=value / guest=value / hotel=value",
            'example' => "http://127.0.0.1:8000/api/admin/search?police=panchlaish",
            'error' => false
        ], 400);
    }
}
