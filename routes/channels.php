<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{user_id}.reminders', function (User $user, $user_id) {
    return (int) $user->id === (int) $user_id;
});
