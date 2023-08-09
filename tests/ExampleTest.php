<?php

it('can test', function () {
    expect(true)->toBeTrue();

    \Pest\Laravel\artisan(\Terpise\Webhook\Commands\WebhookTestCommand::class)->assertExitCode(\Illuminate\Console\Command::SUCCESS);
});
