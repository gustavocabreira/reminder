<?php

namespace App\DTOs;

class HuggyUserDTO
{
    private $id;

    private $name;

    private $email;

    private $avatar;

    private $token;

    private $refresh_token;

    private $expires_in;

    public function fromHuggy($user): self
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->user['photo'];
        $this->token = $user->token;
        $this->refresh_token = $user->refreshToken;
        $this->expires_in = $user->expiresIn;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'huggy_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'token' => $this->token,
            'refresh_token' => $this->refresh_token,
            'expires_in' => $this->expires_in,
        ];
    }
}
