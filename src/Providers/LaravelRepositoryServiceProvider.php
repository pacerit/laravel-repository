<?php

namespace PacerIT\LaravelRepository\Providers;

use Illuminate\Support\ServiceProvider;
use PacerIT\LaravelRepository\Repositories\CoreRepository;
use PacerIT\LaravelRepository\Repositories\Criteria\CoreRepositoryCriteria;
use PacerIT\LaravelRepository\Repositories\Criteria\Interfaces\CoreRepositoryCriteriaInterface;
use PacerIT\LaravelRepository\Repositories\Interfaces\CoreRepositoryInterface;

/**
 * Class LaravelRepositoryServiceProvider.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-05
 */
class LaravelRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__.'/../../resources/config/laravel-repository.php';
        $this->publishes([
            $path => $this->app->configPath('laravel-repository.php'),
        ]);

        $this->mergeConfigFrom($path, 'laravel-repository');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CoreRepositoryInterface::class, CoreRepository::class);
        $this->app->bind(CoreRepositoryCriteriaInterface::class, CoreRepositoryCriteria::class);
    }
}
