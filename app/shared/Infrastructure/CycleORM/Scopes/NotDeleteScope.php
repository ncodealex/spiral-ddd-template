<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Infrastructure\CycleORM\Scopes;

use Cycle\ORM\Select\QueryBuilder;
use Cycle\ORM\Select\ScopeInterface;

/**
 * Not delete scope
 */
class NotDeleteScope implements ScopeInterface
{

    /**
     * @param QueryBuilder $query
     * @return void
     */
    public function apply(QueryBuilder $query): void
    {
        $query->where('deleted_at', '=', null);
    }
}
