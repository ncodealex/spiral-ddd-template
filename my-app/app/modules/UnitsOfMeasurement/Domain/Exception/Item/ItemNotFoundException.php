<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Domain\Exception\Item;

use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Shared\Domain\Exception\ResourceNotFoundException;


/**
 * Class ItemNotFoundException
 */
final class ItemNotFoundException extends ResourceNotFoundException
{
    protected string $entity = Item::ROLE;
}
