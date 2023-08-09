<?php

namespace Terpise\Webhook\Commands;

use Terpise\Webhook\Jobs\WebhookJob;
use Terpise\Webhook\WebhookService;
use Illuminate\Console\Command;

class WebhookRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Webhook run';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = WebhookService::events();
        foreach ($rows as $row) {
            WebhookJob::dispatch($row->id);
        }
        return 0;
    }
}
