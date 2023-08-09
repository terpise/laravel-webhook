<?php

use function Pest\Laravel\artisan;
use Symfony\Component\Console\Command\Command as CommandAlias;

it('command config', function () {
    artisan(\Terpise\Webhook\Commands\WebhookTestCommand::class)
        ->expectsOutput(config('webhook.text'))
        ->assertExitCode(CommandAlias::SUCCESS);
});

it('command set config', function () {
    config()->set('webhook.text', 'Set text');
    artisan(\Terpise\Webhook\Commands\WebhookTestCommand::class)
        ->expectsOutput('Set text')
        ->assertExitCode(CommandAlias::SUCCESS);
});
