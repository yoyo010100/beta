<?php

namespace App\Jobs;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * GenerateQuotationPdfJob — CPU-heavy PDF rendering, off the request thread.
 */
class GenerateQuotationPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 15;

    public int $timeout = 120;

    public function __construct(public QuoteRequest $quoteRequest)
    {
        $this->onQueue('media');
    }

    public function handle(): void
    {
        Log::info("Generating quotation PDF for #{$this->quoteRequest->public_id}");

        // In production:
        // $path = (new QuotationPdfBuilder($this->quoteRequest))->store('quotations');
        // $this->quoteRequest->forceFill(['pdf_path' => $path])->saveQuietly();
    }
}
