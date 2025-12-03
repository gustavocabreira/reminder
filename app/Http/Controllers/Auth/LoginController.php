<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redireciona para o provedor de autenticação Huggy
     */
    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->scopes([
            'install_app',
            'read_agent_profile',
        ])->stateless()->redirect();
    }

    /**
     * Processa o callback do Huggy
     */
    public function callback(Request $request, LoginAction $action): RedirectResponse
    {
        $accessToken = $action->execute($request);

        $redirectUrl = sprintf('%s/auth/login?access_token=%s', config('app.frontend_url'), $accessToken['access_token']);

        return response()->redirectTo($redirectUrl);
    }

    /**
     * Desloga o usuário
     */
    public function logout(Request $request): JsonResponse
    {
        request()->user()->tokens()->delete();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
