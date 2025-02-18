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
 * Class UnitUpdateCommand
 *
 * @extends  Command<string|false> Success status
 */
class UnitUpdateCommand extends Command
{
    public function __construct(
        public Uuid           $id,
        public ?Name          $name = null,
        public ?string        $shortName = null,
        public ?PackagingType $forPackaging = null,
        public ?int           $precision = null,
        public ?bool          $isDefault = null,
        public ?int           $sort = null
    )
    {
    }
}
