<?php

namespace Terpise\Webhook\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Terpise\Webhook\Webhook
 */
class Webhook extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Terpise\Webhook\Webhook::class;
    }
}
