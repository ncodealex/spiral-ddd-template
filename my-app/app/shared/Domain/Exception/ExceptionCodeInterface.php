<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Exception;

interface ExceptionCodeInterface
{
    public const BAD_DATA = 400;
    public const UNPROCESSABLE_ENTITY = 422;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const ERROR = 500;
    const BAD_LOGIC = 400;
}
