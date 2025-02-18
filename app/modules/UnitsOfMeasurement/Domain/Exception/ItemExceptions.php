<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Domain\Exception;

use Modules\UnitsOfMeasurement\Domain\Exception\Item\ItemNotFoundException;


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
