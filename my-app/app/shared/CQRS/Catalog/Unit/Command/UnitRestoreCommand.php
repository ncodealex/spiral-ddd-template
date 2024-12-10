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
use Shared\Domain\ValueObject\Uuid;

/**
 * @extends  Command<bool> Success status
 */
final class UnitRestoreCommand extends Command
{
    /**
     * UnitRestoreCommand constructor.
     *
     * @param Uuid $id
     */
    public function __construct(
        public Uuid $id
    )
    {
    }
}
