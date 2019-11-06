<?php

namespace PacerIT\LaravelRepository\Repositories;

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
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function clearCriteria(): CoreRepositoryInterface
    {
        $this->criteria = new Collection();

        return $this;
    }

    /**
     * Return eloquent collection of all records of entity
     * Criteria are not apply in this query.
     *
     * @param array $columns
     *
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-05
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->getEntity()->all($columns);
    }

    /**
     * Return eloquent collection of matching records.
     *
     * @param array $columns
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

        return $this->getEntity()->get($columns);
    }

    /**
     * Get first record.
     *
     * @param array $columns
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

        return $this->getEntity()->first($columns);
    }

    /**
     * Save new entity.
     *
     * @param array $parameters
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

        return $this->getEntity();
    }

    /**
     * Create new model or update existing.
     *
     * @param array $where
     * @param array $values
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

        return $this->getEntity();
    }

    /**
     * Update entity.
     *
     * @param int   $id
     * @param array $parameters
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

        return $this->getEntity();
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

        return $this;
    }

    /**
     * Get first entity record or new entity instance.
     *
     * @param array $where
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function firstOrNew(array $where)
    {
        return $this->getEntity()->firstOrNew($where);
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
     * @return Collection
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function findWhere(array $where, array $columns = ['*']): Collection
    {
        $this->applyCriteria();

        return $this->getEntity()->where($where)->get($columns);
    }

    /**
     * Find where In.
     *
     * @param string $column
     * @param array  $where
     * @param array  $columns
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

        return $this->getEntity()->whereIn($column, $where)->get();
    }

    /**
     * Find where not In.
     *
     * @param string $column
     * @param array  $where
     * @param array  $columns
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

        return $this->getEntity()->whereNotIn($column, $where)->get($columns);
    }

    /**
     * Chunk query results.
     *
     * @param int      $limit
     * @param callable $callback
     * @param array    $columns
     *
     * @return bool
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/10/2019
     */
    public function chunk(int $limit, callable $callback, array $columns = ['*']): bool
    {
        return $this->getEntity()->select($columns)->chunk($limit, $callback);
    }
}
