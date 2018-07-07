<?php

namespace CKSource\CKFinderBridge;

use CKSource\CKFinder\CKFinder;
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

            return;
        }

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->app->bind('ckfinder.connector', function() {
            return new CKFinder(require_once __DIR__.'/config.php');
        });
    }
}