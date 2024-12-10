<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Unit\Command;

use Shared\CQRS\Command;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\PackagingType;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class Group Create Command
 *
 * @extends  Command<string|false> Success status
 */
final class UnitCreateCommand extends Command
{

    public function __construct(
        public ?Uuid          $id,
        public Name           $name,
        public string         $shortName,
        public ?PackagingType $forPackaging = PackagingType::NOT_USED,
        public int            $precision = 0,
        public bool           $isSystem = false,
        public bool           $isDefault = false,
        public int            $sort = 0
    )
    {
    }
}
