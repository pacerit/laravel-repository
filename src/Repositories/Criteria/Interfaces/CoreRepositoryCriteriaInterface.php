<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface CoreRepositoryCriteriaInterface.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-05
 */
interface CoreRepositoryCriteriaInterface
{
    /**
     * Apply criteria on entity.
     *
     * @param Model|Builder $entity
     *
     * @return Model|Builder
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function apply($entity);
}
