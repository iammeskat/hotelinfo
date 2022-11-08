<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'data' => [
                'users' => $users,
            ],
            'message' => 'Successfull',
            'error' => false,
        ], 200);
    }
    public function update(Request $request, $user_id)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'phone' => 'required|numeric',

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
        $user = User::find($user_id);
        if ($user) {

            $user->update([
                'email' => $request['email'],
                'phone' => $request['phone'],
            ]);
            return response()->json([
                'message' => 'Successfully updated',
                'error' => false,
            ], 200);
        }
        return response()->json([
            'message' => 'Data not found',
            'error' => true,
        ], 200);
    }

    public function destroy($user_id)
    {


        $user = User::find($user_id);
        if ($user) {

            $user->delete();
            return response()->json([
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
