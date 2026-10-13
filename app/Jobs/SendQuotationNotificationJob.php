<?php

namespace App\Jobs;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendQuotationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public int $backoff = 10;

    public function __construct(public QuoteRequest $quoteRequest)
    {
        $this->onQueue('notifications');
    }

    public function handle(): void
    {
        Log::info("Asynchronous Notification: New Quote Request #{$this->quoteRequest->public_id} received from {$this->quoteRequest->client_name} ({$this->quoteRequest->company_name}) for country ID: {$this->quoteRequest->country_id}");
        // In production: Mail::to(config('mail.sales_team'))->send(new NewQuoteRequestMail($this->quoteRequest));
    }
}
