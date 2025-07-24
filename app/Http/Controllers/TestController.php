<?php

namespace App\Http\Controllers;

use App\Services\TestService;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function test_methodu(): JsonResponse
    {
        $result = $this->testService->getTestUserNewsImage();

        if (is_array($result) && isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['code']);
        }

        return response()->json($result);
    }

    public function create_user()
    {
        $user = $this->testService->createTestUser();
        return response()->json($user);
    }
}
