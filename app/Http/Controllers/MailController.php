<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\MailService;

class MailController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function sendMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz istek!',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->mailService->sendMail(
            $request->email,
            $request->name,
            $request->subject,
            $request->message
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'] ?? null
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error'] ?? null
            ], $result['code'] ?? 500);
        }
    }

    public function sendBulkMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|min:1|max:50',
            'emails.*' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz istek!',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->mailService->sendBulkMail($request->emails);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null
        ]);
    }

    public function getQueueStatus()
    {
        $result = $this->mailService->getQueueStatus();

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => $result['data'] ?? null
        ]);
    }
}
