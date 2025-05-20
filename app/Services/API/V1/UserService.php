<?php

namespace App\Services\API\V1;

use App\Http\Requests\API\V1\User\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private User $user;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        try {
            $user = $this->user->query()->get();

            return $user;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['role']);

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            $e->getMessage();
        }
    }
}
