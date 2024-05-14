<?php

namespace App\Jobs;

use App\EML;
use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessEmailMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected EmailMessage $emailMessage)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $eml = EML::parse(Storage::readStream($this->emailMessage->file_path));
            $this->emailMessage->update([
                'processing_status' => 'COMPLETE',
                'sender' => $eml->from,
                'recipient' => $eml->to,
                'subject' => $eml->subject,
                'has_attachments' => count($eml->attachments) > 0,
                'is_test' => str_contains($eml->html, 'mycurricula.com'),
            ]);
        } catch (Throwable $e) {
            $this->emailMessage->update([
                'processing_status' => 'FAILED',
                'processing_result' => $e->getMessage(),
            ]);
        }

    }
}
