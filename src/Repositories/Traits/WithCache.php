<?php

namespace PacerIT\LaravelRepository\Repositories\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use PacerIT\LaravelRepository\Repositories\Interfaces\CoreRepositoryInterface;
use ReflectionObject;

/**
 * Trait WithCache.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-08-07
 */
trait WithCache
{
    /**
     * Determine if cache will be skipped in query.
     *
     * @var bool
     */
    protected $skipCache = false;

    /**
     * Determine if skipp auth user tag.
     *
     * @var bool
     */
    protected $skipUserTag = false;

    /**
     * Skip cache.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function skipCache(): CoreRepositoryInterface
    {
        $this->skipCache = true;

        return $this;
    }

    /**
     * Skip auth user tag.
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    public function skipUserTag(): CoreRepositoryInterface
    {
        $this->skipUserTag = true;

        return $this;
    }

    /**
     * Generate cache key.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return string
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    protected function getCacheKey(string $method, array $parameters): string
    {
        // Get actual repository class name.
        $className = get_class($this);

        // Get serialized criteria.
        $criteria = $this->getSerializedCriteria();

        return sprintf(
            '%s@%s_%s-%s',
            $method,
            $className,
            $this->getTag(),
            md5(serialize($parameters).$criteria)
        );
    }

    /**
     * Serialize criteria pushed into repository.
     *
     * @return string
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    protected function getSerializedCriteria(): string
    {
        return serialize(
            $this->getCriteria()->map(
                function ($criteria) {
                    $reflectionClass = new ReflectionObject($criteria);

                    $parameters = [];
                    if ($reflectionClass->getConstructor() !== null) {
                        $parameters = $reflectionClass->getConstructor()->getParameters();
                    }

                    try {
                        $hash = serialize($criteria);
                    } catch (\Exception $exception) {
                        $hash = md5((string) $criteria);
                    }

                    return [
                        'hash'       => $hash,
                        'criteria'   => $reflectionClass->getName(),
                        'properties' => $reflectionClass->getProperties(),
                        'parameters' => $parameters,
                    ];
                }
            )
        );
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
        if ($this->skipCache) {
            return parent::all($columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($columns) {
                return parent::all($columns);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::get($columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($columns) {
                return parent::get($columns);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::first($columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($columns) {
                return parent::first($columns);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::firstOrNew($where);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($where) {
                return parent::firstOrNew($where);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::findWhere($where, $columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($where, $columns) {
                return parent::findWhere($where, $columns);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::findWhereIn($columns, $where, $columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($column, $where, $columns) {
                return parent::findWhereIn($column, $where, $columns);
            }
        );
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
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::findWhereNotIn($column, $where, $columns);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($column, $where, $columns) {
                return parent::findWhereNotIn($column, $where, $columns);
            }
        );
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
        Cache::tags([$this->getTag()])->flush();

        return parent::create($parameters);
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
        Cache::tags([$this->getTag()])->flush();

        return parent::updateOrCreate($where, $values);
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
        Cache::tags([$this->getTag()])->flush();

        return parent::update($id, $parameters);
    }

    /**
     * Delete entity.
     *
     * @param int $id
     *
     * @return CoreRepositoryInterface
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-07-10
     */
    public function delete(int $id): CoreRepositoryInterface
    {
        Cache::tags([$this->getTag()])->flush();

        return parent::delete($id);
    }

    /**
     * Paginate results.
     *
     * @param null   $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 23/01/2020
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::paginate($perPage, $columns, $pageName, $page);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($perPage, $columns, $pageName, $page) {
                return parent::paginate($perPage, $columns, $pageName, $page);
            }
        );
    }

    /**
     * Paginate results (simple).
     *
     * @param null   $perPage
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return mixed
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 23/01/2020
     */
    public function simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if ($this->skipCache || !$this->cacheActive()) {
            return parent::simplePaginate($perPage, $columns, $pageName, $page);
        }

        $cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());

        // Store or get from cache.
        return Cache::tags([$this->getTag()])->remember(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($perPage, $columns, $pageName, $page) {
                return parent::simplePaginate($perPage, $columns, $pageName, $page);
            }
        );
    }

    /**
     * Try to get actual authenticated user ID.
     *
     * @return int
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    private function getTag(): string
    {
        if ($this->skipUserTag) {
            return class_basename($this).'_0';
        }

        foreach ($this->getCacheGuards() as $guard) {
            if (auth($guard)->check()) {
                return class_basename($this).'_'.auth($guard)->user()->getAuthIdentifier();
            }
        }

        return class_basename($this).'_0';
    }

    /**
     * Checking if caching is activated in config file.
     *
     * @return bool
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    private function cacheActive(): bool
    {
        return config('laravel-repository.repository.cache.active', false);
    }

    /**
     * Get cache time (in seconds).
     *
     * @return int
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    private function getCacheTime(): int
    {
        return (int) config('laravel-repository.repository.cache.time', 3600);
    }

    /**
     * Get cache guards to search for auth user ID.
     *
     * @return array
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 2019-08-07
     */
    private function getCacheGuards(): array
    {
        return config('laravel-repository.repository.cache.guards', []);
    }
}
