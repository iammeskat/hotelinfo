<?php

namespace App\Http\Controllers;

use App\Models\ForeignEmployee;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotel;
use App\Models\HotelAuthority;
use App\Models\HotelService;
// use App\Models\Owner;
use App\Models\PoliceStation;
use Exception;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{



    public function index()
    {
        $list = Hotel::leftJoin('users', 'users.id', '=', 'hotels.user_id')
            ->leftJoin('police_stations', 'police_stations.id', '=', 'hotels.police_station_id')
            ->select('hotels.id', 'hotels.name_en', 'hotels.star_level', 'hotels.hotel_phone_number as hotel_phone', 'hotels.hotel_email as hotel_email', 'hotels.address', 'police_stations.name as police_station', 'users.email as user_email', 'users.phone as user_phone')
            ->get();
        return response()->json([
            "data" =>
            [
                "hotels" => $list
            ],
            "message" => "Successfully retrived",
            "error" => false
        ], 200);
    }

    public function indexByPoliceStation($id = false)
    {

        $list = Hotel::where('police_station_id', $id)
            ->leftJoin('users', 'users.id', '=', 'hotels.user_id')
            ->leftJoin('police_stations', 'police_stations.id', '=', 'hotels.police_station_id')
            ->select('hotels.id', 'hotels.name_en', 'hotels.star_level', 'hotels.hotel_phone_number as hotel_phone', 'hotels.hotel_email as hotel_email', 'hotels.address', 'police_stations.name as police_station', 'users.email as user_email', 'users.phone as user_phone')
            ->get();
        return response()->json([
            "data" =>
            [
                "hotels" => $list
            ],
            "message" => "Successfully retrived",
            "error" => false
        ], 200);
    }



    public function show(Request $request, $id = false)
    {
        if ($id == false) {
            $hotel_info = $request['hotel_info']; // from middleware
            $id = $hotel_info['id'];
            $hotel = Hotel::with('services', 'rooms', 'authorities', 'foreignEmployees', 'user')
                ->leftJoin('users', 'users.id', '=', 'hotels.user_id')
                ->select('hotels.*', 'users.email as user_email', 'users.phone as user_phone')
                ->find($id);
        } else {

            $hotel = Hotel::with('services', 'rooms', 'authorities', 'foreignEmployees', 'user', 'guests2')
                ->leftJoin('users', 'users.id', '=', 'hotels.user_id')
                ->select('hotels.*', 'users.email as user_email', 'users.phone as user_phone')
                ->find($id);
        }


        return response()->json([
            "data" => [
                "hotel" => $hotel
            ],
            "message" => "Successfully retrived",
            "error" => false
        ], 200);
    }

    /**
     * create hotel
     * @return json
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:11|unique:users',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:8|max:16|confirmed',

            'name_en' => 'required',
            'name_bn' => 'required',

            'police_station_id' => 'required|exists:police_stations,id',
            'star_level' => 'required',
            'address' => 'required',
            'hotel_phone_number' => 'required',
            'hotel_email' => 'required',
            'website' => 'required',
            'estd' => 'required',
            'facebook' => 'required',
            'other_social_id' => 'required',
            'hotel_license_no' => 'required',
            'hotel_license_reg_date' => 'required',
            'trade_license_no' => 'required',
            'tin_no' => 'required',
            'vat_no' => 'required',
            'bin_no' => 'required',
            'environment_certificate' => 'required',
            'fireservice_certificate' => 'required',
            'manager' => 'required',
            'description_of_foreign_investment' => 'required',
            'no_of_room' => 'required',
            'no_of_officer' => 'required',
            'no_of_employee' => 'required',
            'no_of_cc_camera' => 'required',
            'parking' => 'required',
            'emergency_exit' => 'required',
            'firefighting_system' => 'required',
            'last_date_of_firefighting_ex' => 'required',
            'generator' => 'required',
            'owners_asso_membership' => 'required',
            'review' => 'required',
            'other_info' => 'required',
            'remark' => 'required',

            'authorities' => 'required|array',

            "authorities.*.phone" => "required",
            "authorities.*.email" => "required|email",
            "authorities.*.name" => "required",
            "authorities.*.address" => "required",
            "authorities.*.city" => "required",
            "authorities.*.nid" => "required|min:10|max:17",
            "authorities.*.political_identity" => "required",
            "authorities.*.position" => "required",

            "services.restaurant" => "required",
            "services.bar" => "required",
            "services.gym" => "required",
            "services.swimming_pool" => "required",
            "services.conference_hall" => "required",
            "services.massage_center" => "required",

            "foreign_employees.*.name" => "required",
            "foreign_employees.*.address" => "required",
            "foreign_employees.*.city" => "required",
            "foreign_employees.*.phone_number" => "required",
            "foreign_employees.*.passport" => "required",
            "foreign_employees.*.passport_issue_date" => "required",
            "foreign_employees.*.passport_exp_date" => "required",
            "foreign_employees.*.visa_type" => "required",
            "foreign_employees.*.security_clearance" => "required",
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


        $user = $this->createUser($request, 1);
        if (!$user) {
            return response()->json([
                "message" => "Failed to create a user",
                "error" => true
            ], 500);
        }
        $hotel = Hotel::create([
            'user_id' => $user['id'],
            'name_en' => $request['name_en'],
            'name_bn' => $request['name_bn'],
            'star_level' => $request['star_level'],
            'police_station_id' => $request['police_station_id'],
            'address' => $request['address'],
            'hotel_phone_number' => $request['hotel_phone_number'],
            'hotel_email' => $request['hotel_email'],
            'website' => $request['website'],
            'estd' => $request['estd'],
            'facebook' => $request['facebook'],
            'other_social_id' => $request['other_social_id'],
            'hotel_license_no' => $request['hotel_license_no'],
            'hotel_license_reg_date' => $request['hotel_license_reg_date'],
            'trade_license_no' => $request['trade_license_no'],
            'tin_no' => $request['tin_no'],
            'vat_no' => $request['vat_no'],
            'bin_no' => $request['bin_no'],
            'environment_certificate' => $request['environment_certificate'],
            'fireservice_certificate' => $request['fireservice_certificate'],
            'manager' => $request['manager'],
            'description_of_foreign_investment' => $request['description_of_foreign_investment'],
            'no_of_room' => $request['no_of_room'],
            'no_of_officer' => $request['no_of_officer'],
            'no_of_employee' => $request['no_of_employee'],
            'no_of_cc_camera' => $request['no_of_cc_camera'],
            'parking' => $request['parking'],
            'emergency_exit' => $request['emergency_exit'],
            'firefighting_system' => $request['firefighting_system'],
            'last_date_of_firefighting_ex' => $request['last_date_of_firefighting_ex'],
            'generator' => $request['generator'],
            'owners_asso_membership' => $request['owners_asso_membership'],
            'review' => $request['review'],
            'other_info' => $request['other_info'],
            'remark' => $request['remark'],

        ]);


        // create services
        $services = $request->input('services');
        $newServices = HotelService::create([
            'hotel_id' => $hotel['id'],
            'restaurant' => $services['restaurant'],
            'bar' => $services['bar'],
            'gym' => $services['gym'],
            'swimming_pool' => $services['swimming_pool'],
            'conference_hall' => $services['conference_hall'],
            'massage_center' => $services['massage_center'],

        ]);

        // create authority (owner, CEO, MD, etc)
        $authorities = $request->input('authorities');
        $newAuthorities = [];
        foreach ($authorities as $authority) {

            $newAuthority = HotelAuthority::create([
                'hotel_id' => $hotel['id'],
                'name' => $authority['name'],
                'email' => $authority['email'],
                'phone' => $authority['phone'],
                'address' => $authority['address'],
                'city' => $authority['city'],
                'nid' => $authority['nid'],
                'political_identity' => $authority['political_identity'],
                'position' => $authority['position'],
            ]);
            array_push($newAuthorities, $newAuthority);
        }

        // create foreign employees
        $foreignEmployees = $request->input('foreign_employees');
        $newForeignEmployees = [];
        foreach ($foreignEmployees as $employee) {

            $newEmployee = ForeignEmployee::create([
                'hotel_id' => $hotel['id'],
                'name' => $employee['name'],
                'address' => $employee['address'],
                'city' => $employee['city'],
                'phone_number' => $employee['phone_number'],
                'passport' => $employee['passport'],
                'passport_issue_date' => $employee['passport_issue_date'],
                'passport_exp_date' => $employee['passport_exp_date'],
                'visa_type' => $employee['visa_type'],
                'security_clearance' => $employee['security_clearance'],
            ]);
            array_push($newForeignEmployees, $newEmployee);
        }

        $hotel['authorities'] = $newAuthorities;
        $hotel['services'] = $newServices;
        $hotel['foreign_employees'] = $newForeignEmployees;

        return response()->json([
            'data' => [
                'user' => $user,
                'hotel' => $hotel,
            ],
            'message' => 'Successfully created',
            'error' => false,
        ], 201);
    }



    /**
     * update hotel
     * @return json
     */
    public function update(Request $request, $hotel_id = false)
    {
        if ($hotel_id == false) {
            $hotel_info = $request['hotel_info']; // from middleware
            $hotel_id = $hotel_info['id'];
        }
        $validator = Validator::make($request->all(), [

            'name_en' => 'required',
            'name_bn' => 'required',

            'star_level' => 'required',
            'police_station_id' => 'required|exists:police_stations,id',
            'address' => 'required',
            'hotel_phone_number' => 'required',
            'hotel_email' => 'required',
            'website' => 'required',
            'estd' => 'required',
            'facebook' => 'required',
            'other_social_id' => 'required',
            'hotel_license_no' => 'required',
            'hotel_license_reg_date' => 'required',
            'trade_license_no' => 'required',
            'tin_no' => 'required',
            'vat_no' => 'required',
            'bin_no' => 'required',
            'environment_certificate' => 'required',
            'fireservice_certificate' => 'required',
            'manager' => 'required',
            'description_of_foreign_investment' => 'required',
            'no_of_room' => 'required',
            'no_of_officer' => 'required',
            'no_of_employee' => 'required',
            'no_of_cc_camera' => 'required',
            'parking' => 'required',
            'emergency_exit' => 'required',
            'firefighting_system' => 'required',
            'last_date_of_firefighting_ex' => 'required',
            'generator' => 'required',
            'owners_asso_membership' => 'required',
            'review' => 'required',
            'other_info' => 'required',
            'remark' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'inputs' => $request->input(),
                ],
                'errors' => $validator->errors()->all(),
                'message' => '4Validation Failed',
                'error' => true,

            ], 200);
        }

        $hotel = Hotel::find($hotel_id);

        if ($hotel) {
            $hotel->update([
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'police_station_id' => $request->police_station_id,
                'address' => $request->address,
                'website' => $request->website,
                'room_no' => $request->room_no,
            ]);
            return response()->json([
                'data' => [
                    'hotel' => $hotel,
                ],
                'message' => 'Successfully updated',
                'error' => false,
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid Hotel ID',
            'error' => true,
        ], 200);
    }

    /**
     * delete hotel
     * @return json
     */
    public function destroy($hotel_id)
    {

        $hotel = Hotel::find($hotel_id);

        if ($hotel) {
            $user = User::find($hotel['user_id']);
            $user->delete();
            $hotel->delete();
            return response()->json([
                'message' => 'Successfully deleted',
                'error' => false,
            ], 200);
        }
        return response()->json(
            [
                'message' => 'Data not found',
                'error' => true,
            ],
            200
        );
    }

    private function createUser($data, $role)
    {
        try {
            $user = User::create([
                'phone' => $data['phone'],
                'email' => strtolower(trim($data['email'])),
                'role_id' => $role,
                'email_verified_at' => now(),
                'password' => bcrypt($data['password']),
                'status' => 1,
            ]);
            return $user;
        } catch (Exception $e) {
            return false;
        }
    }

    // other methods
    public function getAuthorities(Request $request)
    {
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $authorities = HotelAuthority::where('hotel_id', $hotelId)->get();

        return response()->json([
            'data' => [
                'hotel' => $authorities,
            ],
            'message' => 'Successfully updated',
            'error' => false,
        ], 200);
    }

    public function createAuthority(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|digits:11|unique:users',
            'email' => 'email|required|unique:users',
            'address' => 'required',
            'city' => 'required',
            'nid' => 'required',
            'political_identity' => 'required',
            'position' => 'required'

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
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $authority = HotelAuthority::create([
            'hotel_id' => $hotelId,
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'address' => $request['address'],
            'city' => $request['city'],
            'nid' => $request['nid'],
            'political_identity' => $request['political_identity'],
            'position' => $request['position'],
        ]);
        return response()->json([
            'data' => [
                'authority' => $authority,
            ],
            'message' => 'Successfull',
            'error' => false,
        ], 201);
    }

    public function updateAuthority(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|digits:11|unique:users',
            'email' => 'email|required|unique:users',
            'address' => 'required',
            'city' => 'required',
            'nid' => 'required',
            'political_identity' => 'required',
            'position' => 'required'

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
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $authority = HotelAuthority::where('id', $id)->where('hotel_id', $hotelId)->first();

        if ($authority) {

            $authority->update([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'address' => $request['address'],
                'city' => $request['city'],
                'nid' => $request['nid'],
                'political_identity' => $request['political_identity'],
                'position' => $request['position'],
            ]);
            return response()->json([
                'data' => [
                    'authority' => $authority,
                ],
                'message' => 'Successfull',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Invalid authority id',
            'error' => true,
        ], 400);
    }
    public function destroyAuthority(Request $request, $id)
    {
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];
        $authority = HotelAuthority::where('id', $id)->where('hotel_id', $hotelId)->first();

        if ($authority) {

            $authority->delete();
            return response()->json([
                'data' => [
                    'authority' => $authority,
                ],
                'message' => 'Deleted',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Invalid authority id',
            'error' => true,
        ], 400);
    }

    public function getFEmployees(Request $request)
    {
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $em = ForeignEmployee::where('hotel_id', $hotelId)->get();
        return response()->json([
            'data' => [
                'foreign_employees' => $em,
            ],
            'message' => 'Success',
            'error' => false,
        ], 200);
    }

    public function createFEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'passport' => 'required',
            'passport_issue_date' => 'required',
            'passport_exp_date' => 'required',
            'visa_type' => 'required',
            'security_clearance' => 'required',

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
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $fem = ForeignEmployee::create([
            'hotel_id' => $hotelId,
            'name' => $request['name'],
            'address' => $request['address'],
            'city' => $request['city'],
            'phone_number' => $request['phone_number'],
            'passport' => $request['passport'],
            'passport_issue_date' => $request['passport_issue_date'],
            'passport_exp_date' => $request['passport_exp_date'],
            'visa_type' => $request['visa_type'],
            'security_clearance' => $request['security_clearance'],
        ]);
        return response()->json([
            'data' => [
                'foreign_employee' => $fem,
            ],
            'message' => 'Successfull',
            'error' => false,
        ], 201);
    }

    public function updateFEmployee(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'passport' => 'required',
            'passport_issue_date' => 'required',
            'passport_exp_date' => 'required',
            'visa_type' => 'required',
            'security_clearance' => 'required',

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
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $fem  = ForeignEmployee::where('hotel_id', $hotelId)->where('id', $id)->first();

        if ($fem) {
            $fem->update([
                'hotel_id' => $hotelId,
                'name' => $request['name'],
                'address' => $request['address'],
                'city' => $request['city'],
                'phone_number' => $request['phone_number'],
                'passport' => $request['passport'],
                'passport_issue_date' => $request['passport_issue_date'],
                'passport_exp_date' => $request['passport_exp_date'],
                'visa_type' => $request['visa_type'],
                'security_clearance' => $request['security_clearance'],
            ]);
            return response()->json([
                'data' => [
                    'foreign_employee' => $fem,
                ],
                'message' => 'Successfull',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Invalid employee id',
            'error' => false,
        ], 400);
    }

    public function destroyFEmployee(Request $request, $id)
    {
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $fem  = ForeignEmployee::where('hotel_id', $hotelId)->where('id', $id)->first();

        if ($fem) {
            $fem->delete();
            return response()->json([
                'data' => [
                    'foreign_employee' => $fem,
                ],
                'message' => 'Successfull',
                'error' => false,
            ], 200);
        }
        return response()->json([

            'message' => 'Invalid employee id',
            'error' => false,
        ], 400);
    }

    public function updateServices(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'restaurant' => 'required|numeric',
            'bar' => 'required|numeric',
            'gym' => 'required|numeric',
            'swimming_pool' => 'required|numeric',
            'conference_hall' => 'required|numeric',
            'massage_center' => 'required|numeric'

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
        $hotelInfo = $request['hotel_info']; // from middleware
        $hotelId = $hotelInfo['id'];

        $services = HotelService::where('hotel_id', $hotelId)->first();
        $services->update([
            'restaurant' => $request['restaurant'],
            'bar' => $request['bar'],
            'gym' => $request['gym'],
            'swimming_pool' => $request['swimming_pool'],
            'conference_hall' => $request['conference_hall'],
            'massage_center' => $request['massage_center']
        ]);

        return response()->json([
            'data' => [
                'services' => $services,
            ],
            'message' => 'success',
            'error' => false
        ]);
    }
}
