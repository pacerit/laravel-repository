<?php

namespace PacerIT\LaravelRepository\Repositories\Exceptions;

use Throwable;

/**
 * Class RepositoryEntityException.
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 2019-07-05
 */
class RepositoryEntityException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Given class (:namespace) must be instance of Model class!';

    /**
     * @var int
     */
    protected $code = 500;

    /**
     * RepositoryEntityException constructor.
     *
     * @param string|null    $namespace
     * @param Throwable|null $previous
     */
    public function __construct(
        ?string $namespace,
        ?Throwable $previous = null
    ) {
        $message = strtr(
            $this->message,
            [
                ':namespace' => $namespace,
            ]
        );
        parent::__construct(
            $message,
            $this->code,
            $previous
        );
    }
}
