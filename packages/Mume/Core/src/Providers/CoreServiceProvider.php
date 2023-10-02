<?php

namespace Mume\Core\Providers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Mume\Core\Exceptions\CoreHandler;
use Mume\Core\Helpers\LoggingHelper;
use Mume\Core\Repositories\BaseRepository;
use Mume\Core\Repositories\Interfaces\BaseRepositoryInterface;
use Mume\Core\Repositories\Interfaces\Role\RoleRepositoryInterface;
use Mume\Core\Repositories\Interfaces\User\UserRepositoryInterface;
use Mume\Core\Repositories\Role\RoleRepository;
use Mume\Core\Repositories\User\UserRepository;
use Mume\Core\Services\Interfaces\Upload\UploadServiceInterface;
use Mume\Core\Services\Interfaces\User\UserServiceInterface;
use Mume\Core\Services\Upload\UploadService;
use Mume\Core\Services\User\UserService;

/**
 * Class CoreServiceProvider
 */
class CoreServiceProvider extends BaseServiceProvider
{
    /**
     * Boot services
     *
     * @throws BindingResolutionException
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS') == true) {
            $url->forceScheme('https');
        }

        // Register exception
        $this->app->singleton(
            ExceptionHandler::class,
            CoreHandler::class
        );

        // Register config
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'core');
        $this->mergeConfigRecursiveFrom(__DIR__.'/../config/logging.php', 'logging');
        $this->mergeConfigRecursiveFrom(__DIR__.'/../config/auth.php', 'auth');
        $this->mergeConfigRecursiveFrom(__DIR__.'/../config/core.php', 'core');
        $this->mergeConfigRecursiveFrom(__DIR__.'/../config/filesystems.php', 'filesystems', true);

        // Register route
        $this->mapApiRoutes(__DIR__.'/../routes/api.php');
        $this->mapWebRoutes(__DIR__.'/../routes/web.php');

        //Custom to logs query form database
        $this->databaseLogger();
    }

    /**
     * Log database query
     */
    protected function databaseLogger()
    {
        try {
            if ((bool) config::get('database.log') == true) {
                DB::listen(function ($query) {
                    Log::channel(LoggingHelper::SQL_LOG_CHANNEL)->debug(
                        $query->sql,
                        $query->bindings,
                    );
                });
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Register services
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UploadServiceInterface::class, UploadService::class);
    }
}
