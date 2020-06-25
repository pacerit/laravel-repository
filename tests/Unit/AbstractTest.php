<?php

namespace PacerIT\LaravelRepository\Tests\Unit;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;
use PacerIT\LaravelRepository\Tests\Resources\Repositories\CachedTestRepository;
use PacerIT\LaravelRepository\Tests\Resources\Repositories\TestRepository;

/**
 * Class AbstractTest.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 08/06/2020
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @var TestRepository
     */
    protected $testRepository;

    /**
     * @var CachedTestRepository
     */
    protected $cachedTestRepository;

    /**
     * Set up test.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../Resources/database/migrations');
        $this->withFactories(__DIR__.'/../Resources/database/factories');

        $this->artisan('migrate')->run();
        $this->artisan('cache:clear')->run();

        $this->testRepository = $this->app->make(TestRepository::class);
        $this->cachedTestRepository = $this->app->make(CachedTestRepository::class);
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            "PacerIT\LaravelRepository\Providers\LaravelRepositoryServiceProvider",
        ];
    }
}
