<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LimitCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-31
 */
class LimitCriteria extends CoreRepositoryCriteria
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * LimitCriteria constructor.
     *
     * @param int $limit
     */
    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

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
    public function apply($entity)
    {
        return $entity->limit($this->limit);
    }
}
