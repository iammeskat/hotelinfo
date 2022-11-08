<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PoliceStation;
use Illuminate\Support\Facades\Validator;

class PoliceController extends Controller
{

    public function index()
    {
        $policeStations = PoliceStation::all();
        return response()->json([
            "data" => $policeStations,
            "error" => false,
        ], 200);
    }

    /**
     * show police station
     * @return json
     */
    public function show($ps_id)
    {

        $police = PoliceStation::with('hotels')->find($ps_id);


        return response()->json([
            'data' => [
                'police' => $police
            ],
            'message' => 'Successfully retrived',
            'error' => false,
        ], 200);
    }

    /**
     * create police station
     * @return json
     */
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            '*.name' => 'required',
            '*.district' => 'required'

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

        $stations = $request->input();
        $insertedData = [];
        foreach ($stations as $station) {
            $policeStation = PoliceStation::create([
                // 'user_id' => $user->id,
                'name' => $station['name'],
                'district' => $station['district'],
            ]);
            array_push($insertedData, $policeStation);
        }


        return response()->json([
            'data' => [
                'policeStations' => $insertedData
            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }

    /**
     * update police station
     * @return json
     */
    public function update(Request $request, $ps_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'district' => 'required'

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
        $police = PoliceStation::find($ps_id);
        if ($police) {

            $police->update([
                'name' => $request->name,
                'district' => $request->district,
            ]);


            return response()->json([
                'data' => [
                    'policeStation' => $police
                ],
                'message' => 'Successfully updated',
                'error' => false,
            ], 201);
        }
        return response()->json([
            'message' => 'Data not found',
            'error' => true,
        ], 200);
    }

    /**
     * delete police station
     * @return json
     */
    public function destroy($ps_id)
    {

        $police = PoliceStation::find($ps_id);

        if ($police) {
            $data = $police->delete();
            return response()->json([
                'data' => [
                    'policeStation' => $data
                ],
                'message' => 'Successfully deleted',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Invalid police station id',
            'error' => true,
        ], 400);
    }
}
