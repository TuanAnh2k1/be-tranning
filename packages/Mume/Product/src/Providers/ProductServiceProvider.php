<?php

namespace Mume\Product\Providers;

use Mume\Core\Providers\BaseServiceProvider;
use Mume\Product\Repositories\ProductRepository;
use Mume\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Mume\Product\Services\ProductService;
use Mume\Product\Services\Interfaces\ProductServiceInterface;

/**
 * Class ProductServiceProvider
 */
class ProductServiceProvider extends BaseServiceProvider
{
    /**
     * Boot services
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // Register route
        $this->mapApiRoutes(__DIR__.'/../routes/api.php');
    }

    /**
     * Register services
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }
}
