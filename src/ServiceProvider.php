<?php

namespace Metadata;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class ServiceProvider  extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}