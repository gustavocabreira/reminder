<?php

declare(strict_types=1);

use App\Models\Reminder;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Notifications\Messages\MailMessage;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'João Silva',
        'email' => 'joao@example.com',
    ]);

    $this->reminder = Reminder::factory()->create([
        'title' => 'Reunião Importante',
        'scheduled_at' => now()->addDay(),
    ]);

    $this->notification = new ReminderNotification($this->reminder);
});

it('uses mail channel', function () {
    $channels = $this->notification->via($this->user);

    expect($channels)->toBe(['mail']);
});

it('creates mail message with correct greeting', function () {
    $mailMessage = $this->notification->toMail($this->user);

    expect($mailMessage)
        ->toBeInstanceOf(MailMessage::class)
        ->and($mailMessage->greeting)
        ->toBe('Olá, João!');
});

it('extracts first name from full name correctly', function () {
    $user = User::factory()->create(['name' => 'Maria Santos Silva']);
    $mailMessage = $this->notification->toMail($user);

    expect($mailMessage->greeting)->toBe('Olá, Maria!');
});

it('includes reminder title in message', function () {
    $mailMessage = $this->notification->toMail($this->user);

    expect($mailMessage->introLines)
        ->toContain('Você foi lembrado do evento: Reunião Importante');
});

it('includes formatted scheduled date and time', function () {
    $scheduledDate = now()->setDate(2025, 12, 15)->setTime(14, 30);
    $reminder = Reminder::factory()->create([
        'scheduled_at' => $scheduledDate,
    ]);

    $notification = new ReminderNotification($reminder);
    $mailMessage = $notification->toMail($this->user);

    expect($mailMessage->introLines)
        ->toContain('Esse evento acontece em 15/12/2025 14:30');
});

it('includes no action required message', function () {
    $mailMessage = $this->notification->toMail($this->user);

    expect($mailMessage->introLines)
        ->toContain('Nenhuma ação futura é necessária!');
});

it('has correct number of intro lines', function () {
    $mailMessage = $this->notification->toMail($this->user);

    expect($mailMessage->introLines)->toHaveCount(3);
});

it('returns empty array for toArray method', function () {
    $array = $this->notification->toArray($this->user);

    expect($array)->toBe([]);
});

it('can be queued', function () {
    expect($this->notification)
        ->toHaveProperty('connection')
        ->and(in_array(
            Illuminate\Bus\Queueable::class,
            class_uses_recursive(ReminderNotification::class)
        ))->toBeTrue();
});

it('stores reminder instance correctly', function () {
    expect($this->notification->reminder)
        ->toBeInstanceOf(Reminder::class)
        ->and($this->notification->reminder->id)
        ->toBe($this->reminder->id);
});

it('handles single word names correctly', function () {
    $user = User::factory()->create(['name' => 'Carlos']);
    $mailMessage = $this->notification->toMail($user);

    expect($mailMessage->greeting)->toBe('Olá, Carlos!');
});

it('formats complete mail message structure', function () {
    $mailMessage = $this->notification->toMail($this->user);

    expect($mailMessage)
        ->greeting->toBe('Olá, João!')
        ->and($mailMessage->introLines)->toHaveCount(3)
        ->and($mailMessage->actionText)->toBeNull()
        ->and($mailMessage->actionUrl)->toBeNull();
});
