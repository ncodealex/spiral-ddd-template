<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Domain;

use Shared\Domain\ValueObject\Category\CategoryOwner;

interface RebuildTreeServiceInterface
{
    public function rebuild(CategoryOwner $owner): bool;
}
