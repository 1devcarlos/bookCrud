<?php

namespace App\Services;
use App\Repositories\UserRepository;
use Hash;

class AuthService
{
  protected $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function register(array $data)
  {
    //Encrypta o password.
    $data['password'] = Hash::make($data['password']);

    // Chama o método o repositorio que cria usuário.
    $user = $this->userRepository->createUser($data);

    return $user;
  }

  public function login(array $credentials)
  {
    //Tenta autenticar o usuario.
    $token = auth('api')->attempt($credentials);

    if (!$token) {
      return [
        'status' => 'error',
        'message' => 'Invalid Credentials',
        'token' => null,
      ];
    }

    // Pega o usuário autenticado
    $user = auth('api')->user();

    return [
      'status' => 'success',
      'user' => $user,
      'token' => $token,
    ];
  }

  public function logout()
  {
    auth('api')->logout();
    return [
      'status' => 'success',
      'message' => 'Successfully logged out',
    ];
  }
}