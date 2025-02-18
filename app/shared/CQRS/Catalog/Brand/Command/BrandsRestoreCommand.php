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
use Shared\Domain\ValueObject\Uuid;

/**
 * @extends  Command<bool> Success status
 */
final class BrandsRestoreCommand extends Command
{
    /**
     * BrandsRestoreCommand constructor.
     *
     * @param Uuid[] $ids
     */
    public function __construct(
        public array $ids
    )
    {
    }
}
