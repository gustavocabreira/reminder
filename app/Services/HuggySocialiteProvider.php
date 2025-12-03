<?php

declare(strict_types=1);

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

final class HuggySocialiteProvider extends AbstractProvider implements ProviderInterface
{
    public function getScopes()
    {
        return ['install_app read_agent_profile'];
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://auth.huggy.app/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://auth.huggy.app/oauth/access_token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.huggy.app/v3/agents/profile', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'] ?? null,
            'name' => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
            'avatar' => $user['avatar'] ?? null,
        ]);
    }
}
