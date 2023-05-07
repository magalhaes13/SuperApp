<?php

namespace App\Http\Controllers;

use App\Exceptions\LoginInvalidException;
use App\Exceptions\ResetPasswordTokenInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Exceptions\VerifyEmailTokenInvalidException;
use App\Http\Requests\AuthForgotPasswordRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthResetPasswordRequest;
use App\Http\Requests\AuthVerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param AuthLoginRequest $request
     * @return UserResource
     * @throws LoginInvalidException
     */
    public function login(AuthLoginRequest $request)
    {
        $input = $request->validated();
        $token = $this->authService->login($input['email'], $input['password']);

        return (new UserResource(auth()->user()))->additional($token);
    }

    /**
     * @param AuthRegisterRequest $request
     * @return UserResource
     * @throws UserHasBeenTakenException
     */
    public function register(AuthRegisterRequest $request)
    {
        $input = $request->validated();
        $user = $this->authService->register($input['first_name'], $input['last_name'] ?? '', $input['email'], $input['password']);

        return new UserResource($user);
    }

    /**
     * @param AuthVerifyEmailRequest $request
     * @return UserResource
     * @throws VerifyEmailTokenInvalidException
     */
    public function verifyEmail(AuthVerifyEmailRequest $request)
    {
        $input = $request->validated();
        $user = $this->authService->verifyEmail($input['token']);

        return new UserResource($user);
    }

    /**
     * @param AuthForgotPasswordRequest $request
     * @return string
     */
    public function forgotPassword(AuthForgotPasswordRequest $request)
    {
        $input = $request->validated();
        return $this->authService->forgotPassword($input['email']);
    }

    /**
     * @param AuthResetPasswordRequest $request
     * @return string
     * @throws ResetPasswordTokenInvalidException
     */
    public function resetPassword(AuthResetPasswordRequest $request)
    {
        $input = $request->validated();
        return $this->authService->resetPassword($input['email'], $input['password'], $input['token']);
    }

}
