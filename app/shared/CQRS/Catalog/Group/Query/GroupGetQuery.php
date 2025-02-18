<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Group\Query;


use Shared\CQRS\Query;
use Shared\Domain\ValueObject\Uuid;

/**
 * Class GroupGetQuery
 *
 * @extends  Query<GroupDTO>
 */
final class GroupGetQuery extends Query
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
