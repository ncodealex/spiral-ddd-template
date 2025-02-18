<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Exception;


use InvalidArgumentException;

/**
 * Class InvalidArgumentException
 *
 * Used when an invalid argument is passed to a method.
 * Use in cases where the argument is not of the expected type or is out of the accepted range.
 *
 */
class InvalidInputException extends InvalidArgumentException
{
    protected $code = ExceptionCodeInterface::UNPROCESSABLE_ENTITY;
}
