<?php


namespace App\Services;


use App\Exceptions\UserHasBeenTakenException;
use App\Models\User;

class UserService
{
    /**
     * @param User $user
     * @param array $input
     * @return User|null
     * @throws UserHasBeenTakenException
     */
    public function update(User $user, array $input)
    {
        $checkEmailUser = User::where('email', $input['email'])->where('email', '!=', $user->email)->exists();

        if (!empty($input['email']) && $checkEmailUser) {
            throw new UserHasBeenTakenException();
        }

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $user->fill($input);
        $user->save();

        return $user->fresh();
    }
}
