<?php

namespace App\Services;

use App\Events\ForgotPassword;
use App\Events\UserRegistered;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\ResetPasswordTokenInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Exceptions\VerifyEmailTokenInvalidException;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class AuthService
{
    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws LoginInvalidException
     */
    public function login(string $email, string $password)
    {
        $login = [
            'email'    => $email,
            'password' => $password,
        ];

        if (!$token = auth()->attempt($login)) {
            throw new LoginInvalidException();
        }

        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];
    }

    public function register(string $firstName, string $lastName, string $email, string $password)
    {
        $user = User::where('email', $email)->exists();
        if (!empty($user)) {
            throw new UserHasBeenTakenException();
        }

        $userPassword = bcrypt($password ?? Str::random(length:10));

        $user = User::create([
            'first_name'         => $firstName,
            'last_name'          => $lastName,
            'email'              => $email,
            'password'           => $userPassword,
            'confirmation_token' => Str::random(60)
        ]);

        event(new UserRegistered($user));

        return $user;
    }

    /**
     * @param string $token
     * @return mixed
     * @throws VerifyEmailTokenInvalidException
     */
    public function verifyEmail(string $token)
    {
        $user = User::where('confirmation_token', $token)->first();
        if (empty($user)) {
            throw new VerifyEmailTokenInvalidException();
        }

        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @return string
     */
    public function forgotPassword(string $email)
    {
        $user = User::where('email', $email)->firstOrFail();

        $token = Str::random(length:60);

        PasswordReset::create([
            'email' => $user->email,
            'token' => $token,
        ]);

        event(new ForgotPassword($user, $token));

        return '';
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $token
     * @return string
     * @throws ResetPasswordTokenInvalidException
     */
    public function resetPassword(string $email, string $password, string $token)
    {
        $passReset = PasswordReset::where('email', $email)->where('token', $token)->first();

        if (empty($passReset)) {
            throw new ResetPasswordTokenInvalidException();
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->password = bcrypt($password);
        $user->save();

        PasswordReset::where('email', $email)->delete();

        return '';
    }






}
