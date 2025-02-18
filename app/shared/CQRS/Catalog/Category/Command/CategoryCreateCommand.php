<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Category\Command;

use Shared\CQRS\Command;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class Group Create Command
 *
 * @extends  Command<string|false> Success status
 */
final class CategoryCreateCommand extends Command
{

    public function __construct(
        public ?Uuid   $id,
        public Name    $name,
        public ?string $placeholder = null,
        public ?string $comment = null,
        public ?Uuid   $parentId = null,
        public bool    $isDefault = false
    )
    {
    }
}
