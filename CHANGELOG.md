# Changelog
## v.1.0.9
    - possibility to disable cache from .env
    - update WithCache trait
    - fix problem when Model was not reset after function call
## v.1.0.8
    - add paginate() and simplePaginate() functions - both with caching option
    - fix popCriteria() function - applied criteria was not cleared properly
## v.1.0.7
    - fix misspel in first() function in WithCache trait
    - fix clearCriteria() function
## v.1.0.6
    - fix first() function in WithCache trait
    - fix wrong configuration file name
## v.1.0.5
    - add count() function
## v.1.0.4
    - fix first() function
## v.1.0.3
    - update create cache key - now is namespace of repository class insted of name. This
    prevent overwrite if you have multi repository with this same class name.
## v.1.0.2
    - fix problem with flushing cache in Repository - now is flushed separately for
    each repository class
## v.1.0.1
    - add chunk() function
## v.1.0.0
    - initial release 