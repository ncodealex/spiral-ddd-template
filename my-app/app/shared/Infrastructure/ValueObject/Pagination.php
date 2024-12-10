<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Infrastructure\ValueObject;

use JsonSerializable;

final class Pagination implements JsonSerializable
{
    public const DEFAULT_LIMIT = 50;
    public const ALLOWED_LIMITS = [25, 50, 100, 200];

    public const LIMIT_NAME = 'limit';
    public const PAGE_NAME = 'page';

    private int $limit;
    private int $page;
    private int $total;
    private int $from;
    private int $to;
    private int $first;
    private int $last;

    public function __construct(
        int $limit,
        int $page,
        int $total
    )
    {
        $this->limit = $limit;
        $this->page = $page;
        $this->total = $total;
        $this->from = ($page - 1) * $limit + 1;
        $this->to = min($this->from + $limit - 1, $total);
        $this->first = 1;
        $this->last = (int)ceil($total / $limit);
    }

    public function jsonSerialize(): array
    {
        return [
            'limit' => $this->limit,
            'total' => $this->total,
            'page' => $this->page,
            'from' => $this->from,
            'to' => $this->to,
            'first' => $this->first,
            'last' => $this->last,
        ];
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
