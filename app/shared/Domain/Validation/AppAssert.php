<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Validation;

use Assert\Assertion;
use Shared\Domain\Exception\AssertException;

class AppAssert extends Assertion
{
    protected static $exceptionClass = AssertException::class;

}
