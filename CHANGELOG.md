# Changelog
## v.2.0.17
    - add functions
        * sum()
    - add functions to work with cache trait
        * count()
        * sum()
## v.2.0.16
    - DateCriteria now accept "column" parameter
    - add new criteria
        * FindWhereOrWhereCriteria
        * FindWhereNotIn
## v.2.0.15
    - update ext-json version to "*" in composer.json file
## v.2.0.14
    - remove unnecessary composer.lock file
    - add new functions
        * doesntHave($relation, $boolean, $callback)
        * orDoesntHave($relation)
        * hasMorph($relation, $types, $operator, $count, $boolean, $callback)
        * orHasMorph($relation, $types, $operator, $count)
        * doesntHaveMorph($relation, $types, $boolean, $callback)
        * orDoesntHaveMorph($relation, $types)
        * whereHasMorph($relation, $types, $callback, $operator, $count)
        * orWhereHasMorph($relation, $types, $callback, $operator, $count)
        * whereDoesntHaveMorph($relation, $types, $callback)
        * orWhereDoesntHaveMorph($relation, $types, $callback)
## v.2.0.13
    - add missing applyCriteria() in chunk() function
## v.2.0.12
    - add new functions
        * has($relation, $operator, $count, $bolean, $callback) - where has relation
        * orHas($relation, $operator, $count) - or where has relation
        * whereHas($relation, $callback, $operator, $count)
        * orWhereHas($relation, $callback, $operator, $count)
        * whereDoesntHave($relation, $callback)
        * orWhereDoesntHave($relation, $callback)
        * withCount($relations)
## v.2.0.11
    - add withTrashed() and onlyTrashed() moethods for Soft deleted entities
    - add possibility to manualy clear cache for repository
    - add possibility to manually set user tag in WithCache trait - for caching data for given user
    when is not autheniticated (i.e. in Jobs, Commands)
## v.2.0.10
    - update criteria serialization in WithCache trait to prevent Closure serialization error
## v.2.0.9
    - possibility to disable cache from .env
    - update WithCache trait
    - fix problem when Model was not reset after function call
## v.2.0.8
    - add paginate() and simplePaginate() functions - both with caching option
    - fix popCriteria() function - applied criteria was not cleared properly
## v.2.0.7
    - fix misspell in first() function in WithCache trait
    - fix clearCriteria() function
## v.2.0.6
    - fix first() function in WithCache trait
    - fix wrong configuration file name
## v.2.0.5
    - add count() function
## v.2.0.4
    - fix first() function
## v.2.0.3
    - update create cache key - now is namespace of repository class insted of name. This
    prevent overwrite if you have multi repository with this same class name.
## v.2.0.2
    - fix problem with flushing cache in Repository - now is flushed separately for
    each repository class
## v.2.0.1
    - add chunk() function
## v.2.0.0
    - initial release 