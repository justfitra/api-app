<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Models\User;
use App\Services\API\V1\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
                'password_confirmation' => [
                    'required',
                    'min:6'
                ]
            ]);

            if ($validator->fails()) {
                return \responder()
                    ->error(['error' => $validator->errors()], 422);
            }
            $user = $this->user->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $user->assignRole('user');

            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $user->name;

            return responder()
                ->success($success)
                ->respond();
        } catch (\Exception $e) {
            return responder()
                ->error()
                ->data([
                    'error' => $e->getMessage()
                ])
                ->respond();
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = $this->user
            ->where('email', $data['email'])
            ->first();

        if (!$user) {
            return responder()
                ->error(['error' => 'User not found'], 404)
                ->respond();
        }

        if (!Hash::check($data['password'], $user->password)) {
            return responder()
                ->error(['error' => 'Invalid credentials'], 401)
                ->respond();
        }

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $success['role'] = $user->getRoleNames();
        $cookie = \cookie('jwt', $success['token'], 68 * 24);

        return responder()
            ->success($success)
            ->respond();
    }
}
