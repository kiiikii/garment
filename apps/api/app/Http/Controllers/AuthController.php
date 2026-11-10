<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  //
  public function login(Request $request)
  {
    $credentials = $request->validate([
      "email" => "required|email",
      "password" => "required",
    ]);

    $user = User::where("email", $credentials["email"])->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(
        [
          "message" => "Invalid credentials",
        ],
        401,
      );
    }

    $token = $user->createToken("api-token")->plainTextToken;

    return response()->json(
      [
        "token" => $token,
        "user" => [
          "id" => $user->id,
          "name" => $user->name,
          "email" => $user->email,
          "role" => $user->role->value,
        ],
      ],
      200,
    );
  }

  public function me(Request $request)
  {
    $user = $request->user();
    return response()->json(
      [
        "data" => [
          "id" => $user->id,
          "name" => $user->name,
          "email" => $user->email,
          "role" => $user->role->value,
        ],
      ],
      200,
    );
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();
    return response()->json(
      [
        "message" => "Successfully logged out",
      ],
      200,
    );
  }
}
