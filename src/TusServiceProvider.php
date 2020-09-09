<?php

namespace Laratus;

use TusPhp\Tus\Server as TusServer;
use TusPhp\Events\TusEvent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaratusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishConfig();
        
        $this->app->singleton('tus-server', function ($app) {
            $server = new TusServer('redis');

            $server
                ->setApiPath('/api/tus') // tus server endpoint.
                ->setUploadDir('/var/storage/'); // uploads dir.
            
            $server->event()->addListener('tus-server.upload.created', function(TusEvent $event) {
                event(new TusCreated($event));
            });

            $server->event()->addListener('tus-server.upload.progress', function(TusEvent $event) {
                event(new TusProgress($event));
            });

            $server->event()->addListener('tus-server.upload.complete', function(TusEvent $event) {
                event(new TusComplete($event));
            });

            $server->event()->addListener('tus-server.upload.merged', function(TusEvent $event) {
                event(new TusMerged($event));
            });
            
            return $server;
        });
    }

    /**
     * Register and publish configuration
     *
     * @return void
     */
    protected function registerPublishConfig()
    {
        $configPath = __DIR__ . '/../config/laratus.php';
        $publishPath = $this->app->configPath('laratus.php');

        $this->mergeConfigFrom($configPath, 'laratus');
        $this->publishes([ $configPath => $publishPath ], 'config');
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerRoutes();


    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }
}
