<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DateCriteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-31
 */
class DateCriteria extends CoreRepositoryCriteria
{
    /**
     * Date from.
     *
     * @var string
     */
    private $dateFrom;

    /**
     * Date to.
     *
     * @var string
     */
    private $dateTo;

    /**
     * DateCriteria constructor.
     *
     * @param null|string $dateFrom
     * @param null|string $dateTo
     */
    public function __construct(
        ?string $dateFrom,
        ?string $dateTo
    ) {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
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
        if ($this->dateFrom !== null) {
            $entity = $entity->where(Model::CREATED_AT, '>=', $this->dateFrom);
        }

        if ($this->dateTo !== null) {
            $entity = $entity->where(Model::CREATED_AT, '<=', $this->dateTo);
        }

        return $entity;
    }
}
