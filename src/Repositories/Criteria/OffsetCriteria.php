<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OffsetCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-08-21
 */
class OffsetCriteria extends CoreRepositoryCriteria
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * OffsetCriteria constructor.
     *
     * @param int $offset
     */
    public function __construct(int $offset)
    {
        $this->offset = $offset;
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
        return $entity->offset($this->offset);
    }
}
