<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Category\Query;

use Shared\CQRS\Query;
use Shared\CQRS\QueryListResponseWrapper;
use Shared\Infrastructure\ValueObject\Pagination;
use Shared\Infrastructure\ValueObject\Sort;

/**
 * Class CategoryListQuery
 *
 * @extends  Query<QueryListResponseWrapper<CategoryDTO>>
 */
final class CategoryListQuery extends Query
{
    public function __construct(
        public ?string $name = null,
        public ?string $order = null,
        public ?string $orderDirection = Sort::ASC,
        public ?int    $limit = Pagination::DEFAULT_LIMIT,
        public ?int    $page = 1,
        public ?bool   $withTrash = false,
    )
    {
    }
}
