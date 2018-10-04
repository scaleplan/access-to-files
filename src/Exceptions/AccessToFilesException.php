<?php

namespace Scaleplan\AccessToFiles\Exceptions;

/**
 * Class AccessToFilesException
 *
 * @package Scaleplan\AccessToFiles\Exceptions
 */
class AccessToFilesException extends \Exception
{
    public const MESSAGE = 'Access to file exception.';

    /**
     * AccessToFilesException constructor.
     *
     * @param string|null $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?? static::MESSAGE, $code, $previous);
    }
}