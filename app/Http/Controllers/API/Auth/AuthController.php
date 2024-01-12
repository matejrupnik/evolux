<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::where("email", $fields["email"])->first();

        if(!$user) {
            return response([
                "message" => "E-mail address does not exist."
            ], 401);
        } elseif (!Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "Incorrect password."
            ], 401);
        }

        $token = $user->createToken("myapptoken")->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    public function register(Request $request) {

        $fields = $request->validate([
            "username" => "required|string|unique:users,username",
            "first_name" => "required|string",
            "middle_name" => "string",
            "last_name" => "string",
            "email" => "required|email:rfc,dns|string|unique:users,email",
            "password" => "required|string|confirmed"
        ]);


        $user = User::create([
            "username" => $fields['username'],
            "first_name" => $fields['first_name'],
            "middle_name" => isset($fields['middle_name']) ? $fields['middle_name'] : null,
            "last_name" => isset($fields['last_name']) ? $fields['last_name'] : null,
            "email" => $fields['email'],
            "password" => Hash::make($fields['password']),
            "is_admin" => 0,
            'media_id' => 1
        ]);

        $keywords = Keyword::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9 , 10])->get();
        $user->keywords()->attach($keywords, ['alignment' => 0.1]);

        $token = $user->createToken("token")->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Sign out successfull.'
        ];
    }
}
