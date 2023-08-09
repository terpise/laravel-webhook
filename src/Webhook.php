<?php

namespace Terpise\Webhook;

class Webhook
{
    /**
     * Indicates if Webhook routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;


    /**
     * Indicates if Webhook migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;


    /**
     * Configure Webhook to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static;
    }

    /**
     * Configure Webhook to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }
}
