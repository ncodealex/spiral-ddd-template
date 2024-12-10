<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Domain;

use Modules\Category\Domain\Entity\Item;
use Shared\Domain\ValueObject\Uuid;

interface CategoryTreeManipulationServiceInterface
{

    public function attachToParent(Item $entity, Uuid $parentId): bool;

    public function moveToRoot(Item $entity): bool;

    public function moveToAnotherParent(Item $entity, Uuid $parentId): bool;
}
