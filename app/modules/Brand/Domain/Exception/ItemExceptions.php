<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Domain\Exception;

use Modules\Brand\Domain\Exception\Item\ItemNotFoundException;


/**
 * Class ItemExceptions
 */
final class ItemExceptions
{

    public static function notFound(): void
    {
        throw new ItemNotFoundException();
    }

}
