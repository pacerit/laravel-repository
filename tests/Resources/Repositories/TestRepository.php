<?php

namespace PacerIT\LaravelRepository\Tests\Resources\Repositories;

use PacerIT\LaravelRepository\Repositories\CoreRepository;
use PacerIT\LaravelRepository\Repositories\Interfaces\CoreRepositoryInterface;
use PacerIT\LaravelRepository\Tests\Resources\Entities\Test;

/**
 * Class TestRepository
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 08/06/2020
 */
class TestRepository extends CoreRepository
{
    /**
     * Model entity class that will be use in repository.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function entity(): string
    {
        return Test::class;
    }
}
