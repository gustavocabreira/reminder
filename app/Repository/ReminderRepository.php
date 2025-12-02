<?php

namespace App\Repository;

use App\Models\Reminder;

final class ReminderRepository
{

  public function create(array $data): Reminder
  {
    $data['company_id'] = 1;
    $data['user_id']    = 4;

    return Reminder::create($data);
  }

  public function update(Reminder $reminder, array $data): Reminder
  {
    $reminder->update($data);

    return $reminder;
  }

  public function delete(Reminder $reminder): bool
  {
    return $reminder->delete();
  }

  public function all()
  {
    return Reminder::query()->orderByDesc('scheduled_at')->get();
  }

  public function find(int $id): ?Reminder
  {
    return Reminder::find($id);
  }
}
