<?php

namespace App\Http\Controllers;

use App\Models\HotelGuest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if (array_key_exists("start-date", $request->input())) {
            if ($request['start-date'] && $request['end-date'] && $request['keyword']) {
                $keyword = $request['keyword'];
                $data['guests'] = HotelGuest::with('hotel', 'room')
                    ->whereDate('arrival_date', '>=', $request['start-date'])
                    ->whereDate('arrival_date', '<=', $request['end-date'])
                    ->where(function ($query) use ($keyword) {
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

                    ->orderBy('id', 'desc')
                    ->paginate(50);
                $data['msg'] = "dates " . $request['start-date'] . " to " . $request['end-date'] . " with " . $request['keyword'];
                return view('dashboard', $data);
            } else if ($request['start-date'] && $request['end-date']) {
                $data['guests'] = HotelGuest::with('hotel', 'room')
                    ->whereDate('arrival_date', '>=', $request['start-date'])
                    ->whereDate('arrival_date', '<=', $request['end-date'])
                    ->orderBy('id', 'desc')
                    ->paginate(50);
                $data['msg'] = "dates " . $request['start-date'] . " to " . $request['end-date'];
                return view('dashboard', $data);
            } else if ($request['keyword']) {
                $keyword = explode(" ", $request['keyword']);
                $data['guests'] = HotelGuest::with('hotel', 'room')
                    ->where(function ($query) use ($keyword) {
                        for ($i = 0; $i < count($keyword); $i++) {
                            $query->orwhere('first_name', 'LIKE',  '%' . $keyword[$i] . '%')
                                ->orwhere('last_name', 'LIKE',  '%' . $keyword[$i] . '%');
                        }
                    })
                    ->orWhereIn('email', $keyword)
                    ->orWhereIn('phone', $keyword)
                    ->orWhereIn('nid_no', $keyword)
                    ->orderBy('id', 'desc')
                    ->paginate(50);
                $data['msg'] = $request['keyword'];
                return view('dashboard', $data);
            }
        }
        $data['guests'] = HotelGuest::with('hotel', 'room')->orderBy('id', 'desc')->paginate(50);
        // return $data;
        return view('dashboard', $data);
    }
}
