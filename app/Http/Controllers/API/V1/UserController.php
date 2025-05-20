<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\StoreUserRequest;
use App\Services\API\V1\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = $this->userService->index();
            return responder()
                ->success($user)
                ->respond();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->store($request);

            return responder()
                ->success(['success' => 'User Successfully Created'])
                ->respond();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
