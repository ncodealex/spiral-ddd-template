<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Group\Command;

use Shared\CQRS\Command;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class GroupUpdateCommand
 *
 * @extends  Command<string|false> Success status
 */
class GroupUpdateCommand extends Command
{
    public function __construct(
        public Uuid    $id,
        public ?Name   $name = null,
        public ?Color  $color = null,
        public ?string $comment = null,
        public ?string $placeholder = null,
        public ?int    $sort = null,
        public bool    $isDefault = false
    )
    {
    }
}
