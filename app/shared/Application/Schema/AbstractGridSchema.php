<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Application\Schema;

use Shared\Infrastructure\ValueObject\Pagination;
use Spiral\DataGrid\GridSchema;
use Spiral\DataGrid\Specification\Filter;
use Spiral\DataGrid\Specification\Pagination\PagePaginator;
use Spiral\DataGrid\Specification\Sorter\Sorter;
use Spiral\DataGrid\Specification\Value;

abstract class AbstractGridSchema extends GridSchema
{
    public function addSort(string $column): self
    {
        $this->addSorter($column, new Sorter($column));
        return $this;
    }

    public function addPagination(): self
    {
        $this->setPaginator(new PagePaginator(Pagination::DEFAULT_LIMIT, Pagination::ALLOWED_LIMITS));
        return $this;
    }

    public function addOneFilterLike(string $queryStringKey, string $byColumn): self
    {
        $this->addFilter(
            $queryStringKey,
            new Filter\Like($byColumn)
        );
        return $this;
    }

    public function addFilterBool(string $qsName, ?string $dbColumn = null): self
    {
        $this->addFilter(
            $qsName,
            new Filter\Equals($dbColumn ?? $qsName, new Value\BoolValue())
        );
        return $this;
    }
}
