<?php

namespace PacerIT\LaravelRepository\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Select2Criteria.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-31
 */
class Select2Criteria extends CoreRepositoryCriteria
{
    /**
     * Phrase to search.
     *
     * @var string
     */
    protected $searchPhrase;

    /**
     * Array of fields to select.
     *
     * @var array
     */
    protected $select;

    /**
     * Fields to search.
     *
     * @var string
     */
    protected $searchField;

    /**
     * Limit for query.
     *
     * @var int
     */
    protected $limit;

    /**
     * Select2Criteria constructor.
     *
     * @param string $searchPhrase
     * @param array  $select
     * @param string $searchField
     * @param int    $limit
     */
    public function __construct(
        string $searchPhrase,
        array $select,
        string $searchField,
        int $limit = 5
    ) {
        $this->searchPhrase = $searchPhrase;
        $this->select = $select;
        $this->searchField = $searchField;
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
        return $entity->select($this->select)
            ->where($this->searchField, 'like', '%'.$this->searchPhrase.'%')
            ->limit($this->limit);
    }
}
