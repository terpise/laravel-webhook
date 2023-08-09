<?php

namespace Terpise\Webhook\Commands;

use Terpise\Webhook\Models\WebhookClient;
use Terpise\Webhook\WebhookClientRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class WebhookMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:make {--name} {--id} {--secret}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a webhook client';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(WebhookClientRepository $webhookClientRepository)
    {
        $name = $this->option('name') ?: $this->ask(
            'What should we name the client?',
            'Client'
        );

        $id = $this->option('id') ?: $this->ask(
            'What should we use for the client ID?',
            $webhookClientRepository->getId()
        );

        $secret = $this->option('secret') ?: $this->ask(
            'What should we use for the client secret?',
            $webhookClientRepository->getSecret()
        );

        if ($webhookClientRepository->idExists($id)) {
            $this->error('Client ID already exists.');
            return CommandAlias::FAILURE;
        }
        $client = $webhookClientRepository->store($name, $secret, $id);

        $this->info('New client created successfully.');

        $this->outputCommand($client);
        return CommandAlias::SUCCESS;
    }

    protected function outputCommand(WebhookClient $client)
    {
        $this->line('<comment>Client ID:</comment> ' . $client->id);
        $this->line('<comment>Client secret:</comment> ' . $client->secret);
    }
}
