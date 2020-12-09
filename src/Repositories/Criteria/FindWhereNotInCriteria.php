<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FindWhereNotInCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 09/12/2020
 */
class FindWhereNotInCriteria extends CoreRepositoryCriteria
{
    /**
     * @var string
     */
    protected $column;

    /**
     * @var array
     */
    protected $notIn;

    /**
     * FindWhereNotInCriteria constructor.
     *
     * @param string $column
     * @param array  $notIn
     */
    public function __construct(string $column, array $notIn)
    {
        $this->column = $column;
        $this->notIn = $notIn;
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
        return $entity->whereNotIn($this->column, $this->notIn);
    }
}
