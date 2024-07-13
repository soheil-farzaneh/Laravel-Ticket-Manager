<?php

namespace Aqayepardakht\TicketManager;

use Illuminate\Support\ServiceProvider;

class TicketServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesMigrations([
            __DIR__.'/../database/migrations/' => database_path('migrations')],
            'ticket-migrations'
        );
        $this->publishes([
            __DIR__.'/../config/ticket.php' => config_path('ticket.php')],
            'ticket-config'
        );
    }

    public function register()
    {
    }
}