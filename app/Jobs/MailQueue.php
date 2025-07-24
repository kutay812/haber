<?php

namespace App\Jobs;

use App\Mail\ContactMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MailQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;
    public string $name;
    public string $body;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $name, string $body = '')
    {
        $this->email = $email;
        $this->name  = $name;
        $this->body  = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("MailQueue: Mail gönderiliyor → {$this->email}");
            Mail::to($this->email)->send(new ContactMail($this->body));
            Log::info("MailQueue: Mail gönderildi → {$this->email}");
        } catch (\Exception $e) {
            Log::error("MailQueue: Mail gönderilemedi → {$this->email}, Hata: " . $e->getMessage());
            // throw $e; // İstersen kuyrukta yeniden denenmesini istersen açabilirsin
        }
    }
}
