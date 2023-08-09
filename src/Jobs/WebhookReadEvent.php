<?php

namespace Terpise\Webhook\Jobs;

use Terpise\Webhook\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhookReadEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {
        $rows = WebhookService::readEvents();
        foreach ($rows as $row) {
            WebhookSendEvent::dispatch($row->id);
        }
        if (WebhookService::hasEvent()) {
            WebhookReadEvent::dispatch();
        }
        return 0;
    }
}
