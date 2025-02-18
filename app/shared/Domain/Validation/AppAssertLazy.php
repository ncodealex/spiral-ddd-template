<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Validation;

use Assert\Assert;
use Shared\Domain\Exception\AssertsException;

class AppAssertLazy extends Assert
{
    protected static $lazyAssertionExceptionClass = AssertsException::class;
}
