<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DataCheckerController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PoliceController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/logout', [AuthController::class, 'logout']);

// Admin's API
Route::middleware('auth:api', 'adminAuth')->prefix('admin')->group(
    function () {

        //Police
        Route::get('/', [PoliceController::class, 'index']); //Police station list
        Route::post('/police-station', [PoliceController::class, 'create']); // create police station
        Route::get('/police-station/{ps_id}', [PoliceController::class, 'show']); //show police station with hotels
        Route::put('/police-station/{ps_id}', [PoliceController::class, 'update']); // update police station
        Route::delete('/police-station/{ps_id}', [PoliceController::class, 'destroy']); //delete police station

        //Hotel
        Route::get('/hotel', [HotelController::class, 'index']); // all hotel list
        Route::post('/hotel', [HotelController::class, 'create']); // create hotel
        Route::get('/hotel/{hotel_id}', [HotelController::class, 'show']); //show hotel info with guests
        Route::put('/hotel/{hotel_id}', [HotelController::class, 'update']); //update hotel
        Route::delete('/hotel/{hotel_id}', [HotelController::class, 'destroy']); // delete hotel

        // hotel list by police station
        Route::get('/hotel-by-police-station/{ps_id}', [HotelController::class, 'indexByPoliceStation']);

        // Guest
        Route::get('/guests-all', [GuestController::class, 'allGuests']); // guest list
        Route::get('/guests-all-present', [GuestController::class, 'allPresentGuests']); // present guest list
        Route::get('/guests-by-police-station/{ps_id}', [GuestController::class, 'guestsByPoliceStation']); // guest list by police station

        // present guest list by police station
        Route::get('/guests-by-police-station-present/{ps_id}', [GuestController::class, 'presentGuestsByPoliceStation']);
        Route::get('/guest-history/{identification}', [GuestController::class, 'guestHistory']); // identification = nid/email/phone

        //User
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{user_id}', [UserController::class, 'update']);
        Route::get('/users/{user_id}', [UserController::class, 'destroy']);

        // Search (All)
        Route::get('/search', [SearchController::class, 'search']);
    }
);


// Hotel's API
Route::middleware('auth:api', 'hotelAuth')->prefix('hotel')->group(
    function () {

        Route::get('/', [HotelController::class, 'show']); //profile
        Route::put('/', [HotelController::class, 'update']); // update hotel info

        //Room
        Route::get('/room', [RoomController::class, 'index']); //room list
        Route::post('/room', [RoomController::class, 'create']); //create room
        Route::get('/room-available', [RoomController::class, 'availableRoom']); // available room
        Route::get('/room/{room_id}', [RoomController::class, 'show']); // show room with guests
        Route::put('/room/{room_id}', [RoomController::class, 'update']); //update room
        Route::delete('/room/{room_id}', [RoomController::class, 'destroy']); //delete room
        Route::get('/room-change-status/{room_id}', [RoomController::class, 'chngStatus']); // change room status

        Route::get('/booking', [BookingController::class, 'index']); // booking list
        Route::post('/booking', [BookingController::class, 'create']); // create booking with guests

        Route::get('/guest', [GuestController::class, 'guestsByHotel']); // guest list by hotel
        Route::post('/guest', [GuestController::class, 'create']); //add guest into booking
        Route::put('/guest/{guest_id}', [GuestController::class, 'update']); //update guest
        Route::delete('/guest/{guest_id}', [GuestController::class, 'destroy']); //delete guest

        Route::get('/authority', [HotelController::class, 'getAuthorities']); // owner / CEO / MD / etc. list
        Route::post('/authority', [HotelController::class, 'createAuthority']); // add new owner/CEO/MD/etc 
        Route::put('/authority/{authoriy_id}', [HotelController::class, 'updateAuthority']); // update owner/CEO/MD/etc 
        Route::delete('/authority/{authoriy_id}', [HotelController::class, 'destroyAuthority']);

        Route::get('foreign-employees', [HotelController::class, 'getFEmployees']); // foreigner employee list
        Route::post('foreign-employees', [HotelController::class, 'createFEmployee']); // add new foreigner employee
        Route::put('foreign-employees/{fe_id}', [HotelController::class, 'updateFEmployee']); // update foreigner employee
        Route::delete('foreign-employees/{fe_id}', [HotelController::class, 'destroyFEmployee']); // delete foreign employee

        Route::put('services', [HotelController::class, 'updateServices']); // update services
    }
);




// Data checker API
Route::middleware('auth:api')->prefix('check')->group(
    function () {
        Route::get('/email/{email_id}', [DataCheckerController::class, 'checkEmail']);
        Route::get('/phone/{phone}', [DataCheckerController::class, 'checkPhone']);
        Route::get('/nid/{nid}', [DataCheckerController::class, 'checkNid']);
    }
);





Route::get('/unautorized', function () {
    return response()->json([
        "message" => "Unauthorized"
    ], 401);
})->name('unauthorized');
