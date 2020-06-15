<?php

namespace Scaleplan\AccessToFiles\Exceptions;

/**
 * Class AccessToFilesException
 *
 * @package Scaleplan\AccessToFiles\Exceptions
 */
class AccessToFilesException extends \Exception
{
    public const MESSAGE = 'Ошибка доступа к файлу.';
    public const CODE = 400;

    /**
     * AccessToFilesException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: static::MESSAGE, $code ?: static::CODE, $previous);
    }
}
