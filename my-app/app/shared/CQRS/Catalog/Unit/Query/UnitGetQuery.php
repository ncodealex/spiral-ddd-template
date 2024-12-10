<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Unit\Query;


use Shared\CQRS\Query;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class UnitGetQuery
 *
 * @extends  Query<UnitDTO>
 */
final class UnitGetQuery extends Query
{
    /**
     * @param Uuid $id
     * @param bool $inTrash
     */
    public function __construct(
        public Uuid $id,
        public bool $inTrash = false,
    )
    {
    }
}
