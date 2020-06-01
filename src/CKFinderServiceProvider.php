<?php

namespace CKSource\CKFinderBridge;

use CKSource\CKFinderBridge\Command\CKFinderDownloadCommand;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel;

class CKFinderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap.
     */
    public function boot()
    {
        if (config('ckfinder.loadRoutes')) {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }
        $this->loadViewsFrom(__DIR__.'/../views', 'ckfinder');

        if ($this->app->runningInConsole()) {
            $this->commands([CKFinderDownloadCommand::class]);

            $this->publishes([
                __DIR__.'/config.php' => config_path('ckfinder.php')
            ], ['ckfinder', 'ckfinder-config']);

            $this->publishes([
                __DIR__.'/../public' => public_path('js')
            ], ['ckfinder', 'ckfinder-assets']);

            $this->publishes([
                __DIR__.'/../views/setup.blade.php' => resource_path('views/vendor/ckfinder/setup.blade.php'),
                __DIR__.'/../views/browser.blade.php' => resource_path('views/vendor/ckfinder/browser.blade.php')
            ], ['ckfinder', 'ckfinder-views']);

            return;
        }

        $this->app->bind('ckfinder.connector', function() {
            if (!class_exists('\CKSource\CKFinder\CKFinder')) {
                throw new \Exception(
                    "Couldn't find CKFinder conector code. ".
                    "Please run `artisan ckfinder:download` command first."
                );
            }

            $ckfinderConfig = config('ckfinder');

            if (is_null($ckfinderConfig)) {
                throw new \Exception(
                    "Couldn't load CKFinder configuration file. ".
                    "Please run `artisan vendor:publish --tag=ckfinder` command first."
                );
            }

            $ckfinder = new \CKSource\CKFinder\CKFinder($ckfinderConfig);

            if (Kernel::MAJOR_VERSION === 4) {
                $this->setupForV4Kernel($ckfinder);
            }

            return $ckfinder;
        });
    }

    /**
     * Prepares CKFinder DI container to use version version 4+ of HttpKernel.
     *
     * @param \CKSource\CKFinder\CKFinder $ckfinder
     */
    protected function setupForV4Kernel($ckfinder)
    {
        $ckfinder['resolver'] = function () use ($ckfinder) {
            $commandResolver = new \CKSource\CKFinderBridge\Polyfill\CommandResolver($ckfinder);
            $commandResolver->setCommandsNamespace(\CKSource\CKFinder\CKFinder::COMMANDS_NAMESPACE);
            $commandResolver->setPluginsNamespace(\CKSource\CKFinder\CKFinder::PLUGINS_NAMESPACE);

            return $commandResolver;
        };

        $ckfinder['kernel'] = function () use ($ckfinder) {
            return new HttpKernel(
                $ckfinder['dispatcher'],
                $ckfinder['resolver'],
                $ckfinder['request_stack'],
                $ckfinder['resolver']
            );
        };
    }
}
