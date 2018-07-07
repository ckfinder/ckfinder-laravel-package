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

            $this->publishes([
                __DIR__.'/config.php' => config_path('ckfinder.php'),
                __DIR__.'/../public' => public_path('js')
            ], 'ckfinder');

            return;
        }

        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'ckfinder');

        $this->app->bind('ckfinder.connector', function() {
            $ckfinderConfig = config('ckfinder');

            if (is_null($ckfinderConfig)) {
                throw new \InvalidArgumentException(
                    "Couldn't load CKFinder configuration file. ".
                    "Please run `artisan vendor:publish --tag=ckfinder` command first."
                );
            }

            return new CKFinder($ckfinderConfig);
        });
    }
}