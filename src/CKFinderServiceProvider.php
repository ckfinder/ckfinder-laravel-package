<?php

namespace CKSource\CKFinderBridge;

use CKSource\CKFinderBridge\Command\CKFinderDownloadCommand;
use Illuminate\Support\ServiceProvider;

class CKFinderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([CKFinderDownloadCommand::class]);
        }
    }
}