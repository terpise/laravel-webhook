<?php

namespace Terpise\Webhook\Commands;

use Terpise\Webhook\WebhookService;
use Illuminate\Console\Command;

class WebhookTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        WebhookService::event([
            "event_time" => now(),
            "object_id" => random_int(1, 100000),
            "object_type" => "users",
        ], 'create');

        return Command::SUCCESS;
    }
}
