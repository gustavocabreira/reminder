<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

final class HuggyController extends Controller
{
    public function getContacts(Request $request): JsonResponse
    {
        $user = $request->user();
        $endpoint = sprintf('%s/contacts', config('services.huggy.api_url'));
        $response = Http::withToken($user)->get($endpoint, $request->all())->json();

        return response()->json([
            'data' => $response,
        ]);
    }

    public function getChats(Request $request): JsonResponse
    {
        $user = $request->user();
        $endpoint = sprintf('%s/chats', config('services.huggy.api_url'));
        $response = Http::withToken($user)->get($endpoint, $request->all())->json();

        return response()->json([
            'data' => $response,
        ]);
    }
}
