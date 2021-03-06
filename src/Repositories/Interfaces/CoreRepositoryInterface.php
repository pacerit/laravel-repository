<?php

namespace PacerIT\LaravelRepository\Repositories\Interfaces;

use Closure;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use PacerIT\LaravelRepository\Repositories\Criteria\Interfaces\CoreRepositoryCriteriaInterface;
use PacerIT\LaravelRepository\Repositories\Exceptions\RepositoryEntityException;

/**
 * Interface CoreRepositoryInterface.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-05
 */
interface CoreRepositoryInterface
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
    public function entity(): string;

    /**
     * Make new entity instance.
     *
     * @throws RepositoryEntityException
     * @throws BindingResolutionException
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function makeEntity(): self;

    /**
     * Get entity instance.
     *
     * @return Model|Builder
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function getEntity();

    /**
     * Set entity instance.
     *
     * @param Model|Builder $entity
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-09-02
     */
    public function setEntity($entity): self;

    /**
     * Push criteria.
     *
     * @param CoreRepositoryCriteriaInterface $criteria
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function pushCriteria(CoreRepositoryCriteriaInterface $criteria): self;

    /**
     * Pop criteria.
     *
     * @param string $criteriaNamespace
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function popCriteria(string $criteriaNamespace): self;

    /**
     * Get criteria.
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function getCriteria(): Collection;

    /**
     * Apply criteria to eloquent query.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function applyCriteria(): self;

    /**
     * Skip using criteria.
     *
     * @param bool $skip
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function skipCriteria(bool $skip): self;

    /**
     * Clear criteria array.
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function clearCriteria(): self;

    /**
     * Return eloquent collection of all records of entity
     * Criteria are not apply in this query.
     *
     * @param array $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Return eloquent collection of matching records.
     *
     * @param array $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function get(array $columns = ['*']): Collection;

    /**
     * Get first record.
     *
     * @param array $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Model|null
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function first(array $columns = ['*']);

    /**
     * Save new entity.
     *
     * @param array $parameters
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function create(array $parameters = []);

    /**
     * Create new model or update existing.
     *
     * @param array $where
     * @param array $values
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-01
     */
    public function updateOrCreate(array $where = [], array $values = []);

    /**
     * Update entity.
     *
     * @param int   $id
     * @param array $parameters
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function update(int $id, array $parameters = []);

    /**
     * Delete entity.
     *
     * @param int $id
     *
     * @throws Exception
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function delete(int $id): self;

    /**
     * Get first entity record or new entity instance.
     *
     * @param array $where
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function firstOrNew(array $where);

    /**
     * Order by records.
     *
     * @param string $column
     * @param string $direction
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-31
     */
    public function orderBy(string $column, string $direction = 'asc');

    /**
     * Relation sub-query.
     *
     * @param array $relations
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-01
     */
    public function with($relations): self;

    /**
     * Begin database transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionBegin(): self;

    /**
     * Commit database transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionCommit(): self;

    /**
     * Rollback transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionRollback(): self;

    /**
     * Find where.
     *
     * @param array $where
     * @param array $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function findWhere(array $where, array $columns = ['*']): Collection;

    /**
     * Find where In.
     *
     * @param string $column
     * @param array  $where
     * @param array  $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function findWhereIn(string $column, array $where, array $columns = ['*']): Collection;

    /**
     * Find where not In.
     *
     * @param string $column
     * @param array  $where
     * @param array  $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function findWhereNotIn(string $column, array $where, array $columns = ['*']): Collection;

    /**
     * Chunk query results.
     *
     * @param int      $limit
     * @param callable $callback
     * @param array    $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return bool
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/10/2019
     */
    public function chunk(int $limit, callable $callback, array $columns = ['*']): bool;

    /**
     * Count results.
     *
     * @param array $columns
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return int
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 19/11/2019
     */
    public function count(array $columns = ['*']): int;

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param string $column
     *
     * @return mixed
     *
     * @author Wiktor Pacer
     */
    public function sum(string $column);

    /**
     * Paginate results.
     *
     * @param null   $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 23/01/2020
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);

    /**
     * Paginate results (simple).
     *
     * @param null   $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @throws BindingResolutionException
     * @throws RepositoryEntityException
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 23/01/2020
     */
    public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);

    /**
     * Get records with trashed entities.
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function withTrashed(): self;

    /**
     * Get only trashed entities.
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function onlyTrashed(): self;

    /**
     * Has relation.
     *
     * @param string       $relation
     * @param string       $operator
     * @param int          $count
     * @param string       $boolean
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): self;

    /**
     * Or hase relation.
     *
     * @param string $relation
     * @param string $operator
     * @param int    $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function orHas($relation, $operator = '>=', $count = 1): self;

    /**
     * Add a relationship count / exists condition to the query.
     *
     * @param $relation
     * @param string       $boolean
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function doesntHave($relation, $boolean = 'and', Closure $callback = null): self;

    /**
     * Add a relationship count / exists condition to the query with an "or".
     *
     * @param $relation
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function orDoesntHave($relation): self;

    /**
     * Where has relation.
     *
     * @param string       $relation
     * @param Closure|null $callback
     * @param string       $operator
     * @param int          $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function whereHas($relation, Closure $callback = null, $operator = '>=', $count = 1): self;

    /**
     * Or where has relation.
     *
     * @param string       $relation
     * @param Closure|null $callback
     * @param string       $operator
     * @param int          $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function orWhereHas($relation, Closure $callback = null, $operator = '>=', $count = 1): self;

    /**
     * Where doesnt have relation.
     *
     * @param string       $relation
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function whereDoesntHave($relation, Closure $callback = null): self;

    /**
     * Or where doesnt have relation.
     *
     * @param string       $relation
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function orWhereDoesntHave($relation, Closure $callback = null): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query.
     *
     * @param $relation
     * @param $types
     * @param string       $operator
     * @param int          $count
     * @param string       $boolean
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function hasMorph($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with an "or".
     *
     * @param $relation
     * @param $types
     * @param string $operator
     * @param int    $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function orHasMorph($relation, $types, $operator = '>=', $count = 1): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query.
     *
     * @param $relation
     * @param $types
     * @param string       $boolean
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function doesntHaveMorph($relation, $types, $boolean = 'and', Closure $callback = null): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with an "or".
     *
     * @param $relation
     * @param $types
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function orDoesntHaveMorph($relation, $types): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with where clauses.
     *
     * @param $relation
     * @param $types
     * @param Closure|null $callback
     * @param string       $operator
     * @param int          $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function whereHasMorph($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
     *
     * @param $relation
     * @param $types
     * @param Closure|null $callback
     * @param string       $operator
     * @param int          $count
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function orWhereHasMorph($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with where clauses.
     *
     * @param $relation
     * @param $types
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function whereDoesntHaveMorph($relation, $types, Closure $callback = null): self;

    /**
     * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
     *
     * @param $relation
     * @param $types
     * @param Closure|null $callback
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 09/09/2020
     */
    public function orWhereDoesntHaveMorph($relation, $types, Closure $callback = null): self;

    /**
     * Count given relation.
     *
     * @param string|array $relations
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 25/06/2020
     */
    public function withCount($relations): self;
}
