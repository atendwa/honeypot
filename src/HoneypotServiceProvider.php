<?php

declare(strict_types=1);

namespace Atendwa\Honeypot;

use Atendwa\Honeypot\Commands\InstallHoneypot;
use Atendwa\Honeypot\Http\Middleware\PreventSpam;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class HoneypotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('prevent-spam', PreventSpam::class);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'honeypot');

        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $directory = __DIR__ . '/../config/honeypot.php';

            $configPath = config_path('honeypot.php');

            $this->publishes([$directory => $configPath], 'config');

            // Publishing the views.
            $directory = __DIR__ . '/../resources/views/components';

            $resourcePath = resource_path('views/vendor/honeypot/components');

            $this->publishes([$directory => $resourcePath], 'views');

            // Register the command if we are using the application via the CLI
            $this->commands(InstallHoneypot::class);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/honeypot.php', 'honeypot');
    }
}
