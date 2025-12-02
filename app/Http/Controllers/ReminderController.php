<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReminderRequest;
use App\Repository\ReminderRepository;

class ReminderController extends Controller
{
    private $repository;

    public function __construct(ReminderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return response()->json([
            'data' => $this->repository->all()
        ]);
    }

    public function store(StoreReminderRequest $request)
    {
        $reminder = $this->repository->create($request->validated());

        return response()->json(['data' => $reminder], 201);
    }

    public function update(Request $request, $id)
    {
        $reminder = $this->repository->find($id);

        if (!$reminder) {
            return response()->json(['message' => 'Reminder not found'], 404);
        }

        $reminder = $this->repository->update($reminder, $request->all());

        return response()->json(['data' => $reminder]);
    }

    public function destroy($id)
    {
        $reminder = $this->repository->find($id);

        if (!$reminder) {
            return response()->json(['message' => 'Reminder not found'], 404);
        }

        $this->repository->delete($reminder);

        return response()->json(['message' => 'Reminder deleted'], 200);
    }
}
