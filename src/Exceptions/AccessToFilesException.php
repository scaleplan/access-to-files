<?php

namespace Scaleplan\AccessToFiles\Exceptions;

use function Scaleplan\Translator\translate;

/**
 * Class AccessToFilesException
 *
 * @package Scaleplan\AccessToFiles\Exceptions
 */
class AccessToFilesException extends \Exception
{
    public const MESSAGE = 'access-to-files.file-access-error';
    public const CODE = 400;

    /**
     * AccessToFilesException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: translate(static::MESSAGE) ?: static::MESSAGE, $code ?: static::CODE, $previous);
    }
}
