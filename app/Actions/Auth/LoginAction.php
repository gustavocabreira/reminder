<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\HuggyUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

final class LoginAction
{
    public function execute($request): array
    {
        $huggyUser = Socialite::driver($request->provider)->stateless()->user();

        $userPayload = (new HuggyUserDTO)->fromHuggy($huggyUser)->toArray();

        $user = User::query()->updateOrCreate(['email' => $huggyUser->email], $userPayload);
        $token = $user->createToken('Huggy')->plainTextToken;

        Auth::login($user);

        return [
            'access_token' => $token,
        ];
    }
}
