<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FindWhereOrWhereCriteria
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 09/12/2020
 */
class FindWhereOrWhereCriteria extends CoreRepositoryCriteria
{
    /**
     * @var array
     */
    protected $where;

    /**
     * @var array
     */
    protected $orWhere;

    /**
     * FindWhereCriteria constructor.
     *
     * @param array $where
     * @param array $orWhere
     */
    public function __construct(array $where, array $orWhere = [])
    {
        $this->where = $where;
        $this->orWhere = $orWhere;
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
        return $entity->where(function ($query) {
            $query->where($this->where);

            foreach ($this->orWhere as $where) {
                $query->orWhere($where);
            }
        });
    }
}
