<?php

namespace PacerIT\LaravelRepository\Repositories;

use Closure;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PacerIT\LaravelRepository\Repositories\Criteria\CoreRepositoryCriteria;
use PacerIT\LaravelRepository\Repositories\Criteria\Interfaces\CoreRepositoryCriteriaInterface;
use PacerIT\LaravelRepository\Repositories\Exceptions\RepositoryEntityException;
use PacerIT\LaravelRepository\Repositories\Interfaces\CoreRepositoryInterface;

/**
 * Class CoreRepository.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-05
 */
abstract class CoreRepository implements CoreRepositoryInterface
{
    /**
     * Application container.
     *
     * @var Container
     */
    protected $app;

    /**
     * Entity class that will be use in repository.
     *
     * @var Model
     */
    protected $entity;

    /**
     * Criteria collection.
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * Determine if criteria will be skipped in query.
     *
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * CoreRepository constructor.
     *
     * @param Container $app
     *
     * @throws RepositoryEntityException
     * @throws BindingResolutionException
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->criteria = new Collection();
        $this->makeEntity();
    }

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
    public function makeEntity(): CoreRepositoryInterface
    {
        // Make new model instance.
        $entity = $this->app->make($this->entity());

        // Checking instance.
        if (!$entity instanceof Model) {
            throw new RepositoryEntityException($this->entity());
        }

        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity instance.
     *
     * @return Model|Builder
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function getEntity()
    {
        return $this->entity;
    }

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
    public function setEntity($entity): CoreRepositoryInterface
    {
        $this->entity = $entity;

        return $this;
    }

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
    public function pushCriteria(CoreRepositoryCriteriaInterface $criteria): CoreRepositoryInterface
    {
        $this->criteria->push($criteria);

        return $this;
    }

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
    public function popCriteria(string $criteriaNamespace): CoreRepositoryInterface
    {
        $this->criteria = $this->criteria->reject(function ($item) use ($criteriaNamespace) {
            return get_class($item) === $criteriaNamespace;
        });

        $this->makeEntity();

        return $this;
    }

    /**
     * Get criteria.
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    /**
     * Apply criteria to eloquent query.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function applyCriteria(): CoreRepositoryInterface
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria instanceof Collection) {
            foreach ($criteria as $c) {
                if ($c instanceof CoreRepositoryCriteriaInterface
                    && $c instanceof CoreRepositoryCriteria) {
                    $this->entity = $c->apply($this->getEntity());
                }
            }
        }

        return $this;
    }

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
    public function skipCriteria(bool $skip): CoreRepositoryInterface
    {
        $this->skipCriteria = $skip;

        return $this;
    }

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
    public function clearCriteria(): CoreRepositoryInterface
    {
        $this->criteria = new Collection();
        $this->makeEntity();

        return $this;
    }

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
    public function all(array $columns = ['*']): Collection
    {
        $results = $this->getEntity()->all($columns);

        $this->makeEntity();

        return $results;
    }

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
    public function get(array $columns = ['*']): Collection
    {
        $this->applyCriteria();

        $results = $this->getEntity()->get($columns);

        $this->makeEntity();

        return $results;
    }

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
    public function first(array $columns = ['*'])
    {
        $this->applyCriteria();

        $results = $this->getEntity()->first($columns);

        $this->makeEntity();

        return $results;
    }

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
    public function create(array $parameters = [])
    {
        $this->entity = $this->getEntity()->newInstance($parameters);
        $this->getEntity()->save();

        $results = $this->getEntity();

        $this->makeEntity();

        return $results;
    }

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
    public function updateOrCreate(array $where = [], array $values = [])
    {
        $this->entity = $this->getEntity()->updateOrCreate($where, $values);

        $results = $this->getEntity();

        $this->makeEntity();

        return $results;
    }

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
    public function update(int $id, array $parameters = [])
    {
        $this->entity = $this->getEntity()->findOrFail($id);
        $this->getEntity()->fill($parameters);
        $this->getEntity()->save();

        $results = $this->getEntity();

        $this->makeEntity();

        return $results;
    }

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
    public function delete(int $id): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->findOrFail($id);
        $this->getEntity()->delete();

        $this->makeEntity();

        return $this;
    }

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
    public function firstOrNew(array $where)
    {
        $results = $this->getEntity()->firstOrNew($where);

        $this->makeEntity();

        return $results;
    }

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
    public function orderBy(string $column, string $direction = 'asc')
    {
        $this->entity = $this->getEntity()->orderBy($column, $direction);

        return $this;
    }

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
    public function with($relations): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->with($relations);

        return $this;
    }

    /**
     * Begin database transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionBegin(): CoreRepositoryInterface
    {
        DB::beginTransaction();

        return $this;
    }

    /**
     * Commit database transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionCommit(): CoreRepositoryInterface
    {
        DB::commit();

        return $this;
    }

    /**
     * Rollback transaction.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-06
     */
    public function transactionRollback(): CoreRepositoryInterface
    {
        DB::rollBack();

        return $this;
    }

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
    public function findWhere(array $where, array $columns = ['*']): Collection
    {
        $this->applyCriteria();

        $results = $this->getEntity()->where($where)->get($columns);

        $this->makeEntity();

        return $results;
    }

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
    public function findWhereIn(string $column, array $where, array $columns = ['*']): Collection
    {
        $this->applyCriteria();

        $results = $this->getEntity()->whereIn($column, $where)->get();

        $this->makeEntity();

        return $results;
    }

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
    public function findWhereNotIn(string $column, array $where, array $columns = ['*']): Collection
    {
        $this->applyCriteria();

        $results = $this->getEntity()->whereNotIn($column, $where)->get($columns);

        $this->makeEntity();

        return $results;
    }

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
    public function chunk(int $limit, callable $callback, array $columns = ['*']): bool
    {
        $this->applyCriteria();

        $results = $this->getEntity()->select($columns)->chunk($limit, $callback);

        $this->makeEntity();

        return $results;
    }

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
    public function count(array $columns = ['*']): int
    {
        $this->applyCriteria();

        $results = $this->getEntity()->count($columns);

        $this->makeEntity();

        return $results;
    }

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param string $column
     *
     * @return mixed
     *
     * @author Wiktor Pacer
     */
    public function sum(string $column)
    {
        $this->applyCriteria();

        $results = $this->getEntity()->sum($column);

        $this->makeEntity();

        return $results;
    }

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
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->applyCriteria();

        $results = $this->getEntity()->paginate($perPage, $columns, $pageName, $page);

        $this->makeEntity();

        return $results;
    }

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
    public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->applyCriteria();

        $results = $this->getEntity()->simplePaginate($perPage, $columns, $pageName, $page);

        $this->makeEntity();

        return $results;
    }

    /**
     * Get records with trashed entities.
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function withTrashed(): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->withTrashed();

        return $this;
    }

    /**
     * Get only trashed entities.
     *
     * @return $this
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function onlyTrashed(): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->onlyTrashed();

        return $this;
    }

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
    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->has($relation, $operator, $count, $boolean, $callback);

        return $this;
    }

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
    public function orHas($relation, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orHas($relation, $operator, $count);

        return $this;
    }

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
    public function doesntHave($relation, $boolean = 'and', Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->doesntHave($relation, $boolean, $callback);

        return $this;
    }

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
    public function orDoesntHave($relation): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orDoesntHave($relation);

        return $this;
    }

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
    public function whereHas($relation, Closure $callback = null, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->whereHas($relation, $callback, $operator, $count);

        return $this;
    }

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
    public function orWhereHas($relation, Closure $callback = null, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orWhereHas($relation, $callback, $operator, $count);

        return $this;
    }

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
    public function whereDoesntHave($relation, Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->whereDoesntHave($relation, $callback);

        return $this;
    }

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
    public function orWhereDoesntHave($relation, Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orWhereDoesntHave($relation, $callback);

        return $this;
    }

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
    public function hasMorph($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->hasMorph($relation, $types, $operator, $count, $boolean, $callback);

        return $this;
    }

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
    public function orHasMorph($relation, $types, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orHasMorph($relation, $types, $operator, $count);

        return $this;
    }

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
    public function doesntHaveMorph($relation, $types, $boolean = 'and', Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->doesntHaveMorph($relation, $types, $boolean, $callback);

        return $this;
    }

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
    public function orDoesntHaveMorph($relation, $types): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orDoesntHaveMorph($relation, $types);

        return $this;
    }

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
    public function whereHasMorph($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->whereHasMorph($relation, $types, $callback, $operator, $count);

        return $this;
    }

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
    public function orWhereHasMorph($relation, $types, Closure $callback = null, $operator = '>=', $count = 1): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orWhereHasMorph($relation, $types, $callback, $operator, $count);

        return $this;
    }

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
    public function whereDoesntHaveMorph($relation, $types, Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->whereDoesntHaveMorph($relation, $types, $callback);

        return $this;
    }

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
    public function orWhereDoesntHaveMorph($relation, $types, Closure $callback = null): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->orWhereDoesntHaveMorph($relation, $types, $callback);

        return $this;
    }

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
    public function withCount($relations): CoreRepositoryInterface
    {
        $this->entity = $this->getEntity()->withCount($relations);

        return $this;
    }
}
