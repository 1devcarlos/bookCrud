<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6'
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'User created successfully',
      'user' => $user,
    ]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    $token = auth('api')->attempt($credentials);
    if (!$token) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
      ], 401);
    }

    $user = auth()->user();
    return response()->json([
      'status' => 'success',
      'user' => $user,
      'token' => $token,
    ]);
  }

  public function logout(Request $request)
  {
    auth('api')->logout();
    return response()->json([
      'status' => 'success',
      'message' => 'Successfully logged out'
    ]);
  }
}
