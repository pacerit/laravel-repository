<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FindWhereCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-31
 */
class FindWhereCriteria extends CoreRepositoryCriteria
{
    /**
     * @var array
     */
    protected $where;

    /**
     * FindWhereCriteria constructor.
     *
     * @param array $where
     */
    public function __construct(array $where)
    {
        $this->where = $where;
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
        return $entity->where($this->where);
    }
}
