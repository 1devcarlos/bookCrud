<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  protected $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  public function register(Request $request)
  {
    // Valida a request
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6',
    ]);

    // Chama o serviço que cadastra o usuário.
    $user = $this->authService->register($request->all());

    return response()->json([
      'status' => 'success',
      'message' => 'User created successfully',
      'user' => $user,
    ]);
  }

  public function login(Request $request)
  {
    // Valida a request.
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);

    // Chama o serviço que loga o usuário.
    $result = $this->authService->login($request->only('email', 'password'));

    if ($result['status'] === 'error') {
      return response()->json($result, 401);
    }

    return response()->json($result);
  }

  public function logout(Request $request)
  {
    // Chama o service que desloga o usuario
    $result = $this->authService->logout();

    return response()->json($result);
  }
}
