<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS;


/**
 * @template TItems
 */
final readonly class QueryListResponseWrapper
{
    /**
     * @param TItems[]    $items
     * @param int         $total
     * @param int         $page
     * @param int         $limit
     * @param array       $filters
     * @param string|null $order
     * @param string|null $orderDirection
     */
    public function __construct(
        public array   $items,
        public int     $total,
        public int     $page,
        public int     $limit,
        public array   $filters = [],
        public ?string $order = null,
        public ?string $orderDirection = null,
    )
    {
    }
}
