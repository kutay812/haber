<?php

namespace App\Services;

use App\Jobs\MailQueue;
use Illuminate\Support\Facades\Log;

class MailService
{
    public function sendMail($email, $name = null, $subject = null, $message = null)
    {
        try {
            if ($name) {
                MailQueue::dispatch($email, $name, $subject, $message)
                    ->delay(now()->addSeconds(5));
            } else {
                MailQueue::dispatch($email)
                    ->delay(now()->addSeconds(5));
            }

            Log::info('Mail kuyruğa eklendi', [
                'email' => $email,
                'name' => $name ?? 'N/A',
                'timestamp' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Mail kuyruğa başarıyla eklendi.',
                'data' => ['queued_at' => now()]
            ];
        } catch (\Exception $e) {
            Log::error('Mail kuyruğa eklenirken hata oluştu', [
                'email' => $email ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Mail gönderilemedi.',
                'error' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    public function sendBulkMail(array $emails)
    {
        $successCount = 0;
        $failedEmails = [];

        foreach ($emails as $index => $email) {
            try {
                MailQueue::dispatch($email)
                    ->delay(now()->addSeconds(5 + ($index * 2)));
                $successCount++;
            } catch (\Exception $e) {
                $failedEmails[] = $email;
                Log::error('Toplu mail - tek email hatası', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Toplu mail kuyruğa eklendi', [
            'total_emails' => count($emails),
            'success_count' => $successCount,
            'failed_count' => count($failedEmails),
            'timestamp' => now()
        ]);

        return [
            'success' => true,
            'message' => 'Toplu mail işlemi tamamlandı.',
            'data' => [
                'total' => count($emails),
                'success' => $successCount,
                'failed' => count($failedEmails),
                'failed_emails' => $failedEmails,
                'queued_at' => now()
            ]
        ];
    }

    public function getQueueStatus()
    {
        // Burada daha gelişmiş monitoring ekleyebilirsin.
        return [
            'success' => true,
            'message' => 'Kuyruk durumu getirildi.',
            'data' => [
                'queue_active' => true,
                'timestamp' => now(),
                'message' => 'Mail kuyruk sistemi aktif'
            ]
        ];
    }
}
