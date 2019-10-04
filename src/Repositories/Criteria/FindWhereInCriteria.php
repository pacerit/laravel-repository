<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FindWhereInCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-31
 */
class FindWhereInCriteria extends CoreRepositoryCriteria
{
    /**
     * @var string
     */
    protected $column;

    /**
     * @var array
     */
    protected $in;

    /**
     * FindWhereInCriteria constructor.
     *
     * @param string $column
     * @param array  $in
     */
    public function __construct(string $column, array $in)
    {
        $this->column = $column;
        $this->in = $in;
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
        return $entity->whereIn($this->column, $this->in);
    }
}
