<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;

class HotelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user['role_id'] == 1) {
            $request['user_info'] = $user;
            $request['hotel_info'] = Hotel::where('user_id', $user['id'])->first();
            return $next($request);
        }

        return response()->json([
            "message" => "You are unable to access this (Hotel)"
        ], 401);
    }
}
