<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Brand\Command;

use Shared\CQRS\Command;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class BrandUpdateCommand
 *
 * @extends  Command<string|false> Success status
 */
class BrandUpdateCommand extends Command
{
    public function __construct(
        public Uuid    $id,
        public Name    $name,
        public ?string $comment = null,
        public bool    $isDefault = false
    )
    {
    }
}
