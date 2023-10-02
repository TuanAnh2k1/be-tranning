<?php

namespace Mume\Core\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\ServiceProvider;

/**
 * Class BaseServiceProvider
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     * Merge the given configuration with the existing configuration merging recursive data structures.
     *   extended variation on mergeConfigFrom
     *
     * @param  string  $path
     * @param  string  $key
     * @param  bool    $isOverride
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function mergeConfigRecursiveFrom(string $path, string $key, bool $isOverride = false)
    {
        if (!($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            if ($isOverride) {
                // Override all config
                $config->set($key, array_replace_recursive(
                    require $path,
                    $config->get($key, [])
                ));
                return;
            }

            $config->set($key, array_merge_recursive(
                require $path,
                $config->get($key, [])
            ));
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     * @param string $path
     *
     * @return void
     */
    protected function mapWebRoutes(string $path)
    {
        $this->app['router']
            ->middleware('web')
            ->group(function () use ($path) {
                $this->loadRoutesFrom($path);
            });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     * @param string $path
     *
     * @return void
     */
    protected function mapApiRoutes(string $path)
    {
        $apiVer = config('app.default_api_version');
        $this->app['router']
            ->middleware('api')
            ->prefix("api/$apiVer")
            ->group(function () use ($path) {
                $this->loadRoutesFrom($path);
            });
    }
}
