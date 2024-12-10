<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Exception;

use LogicException;

/**
 * Class BadLogicException use for bad logic exception
 *  problem with the program logic
 * ( e.g. trying to save a user with an empty name)
 *
 *
 * @package Showrent\Share\Domain\Exception
 */
class BadLogicException extends LogicException
{
    protected $code = ExceptionCodeInterface::BAD_LOGIC;

}
