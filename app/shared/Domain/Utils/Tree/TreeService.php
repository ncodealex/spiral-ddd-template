<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Utils\Tree;

use Doctrine\Common\Collections\Collection;

final class TreeService
{
    public static function buildTree(Collection $items, string $parentId = 'parentId'): array
    {
        return $tree;
    }
}
