# Changelog
## v.3.0.5
    - add withTrashed() and onlyTrashed() moethods for Soft deleted entities
## v.3.0.4
    - add possibility to manualy clear cache for repository
## v.3.0.3
    - add possibility to manually set user tag in WithCache trait - for caching data for given user
    when is not autheniticated (i.e. in Jobs, Commands)
## v.3.0.2
    - update criteria serialization in WithCache trait to prevent Closure serialization error
## v.3.0.1
    - possibility to disable cache from .env
    - update WithCache trait
    - fix problem when Model was not reset after function call
## v.3.0.0
    - update dependencies for Laravel and Lumen 7.x.x compatibility
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