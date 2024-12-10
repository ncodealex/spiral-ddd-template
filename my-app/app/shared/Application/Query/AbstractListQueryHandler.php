<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Application\Query;

use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Select;
use Exception;
use Shared\CQRS\QueryListResponseWrapper;
use Shared\Infrastructure\ValueObject\Pagination;
use Shared\Infrastructure\ValueObject\Sort;
use Spiral\DataGrid\GridFactory;
use Spiral\DataGrid\GridInterface;
use Spiral\DataGrid\GridSchema;
use Spiral\DataGrid\Input\ArrayInput;

/**
 * @template TEntity
 * @template TDto
 */
abstract readonly class AbstractListQueryHandler
{
    public function __construct(
        private GridFactory           $gridFactory,
        protected RepositoryInterface $repository
    )
    {
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress UndefinedInterfaceMethod
     * @return QueryListResponseWrapper<TDto>
     * @throws Exception
     */
    protected function handleCommand(GridSchema $schema, array $arrayInput, array $selectScope = []): QueryListResponseWrapper
    {
        /** @var Select $select */
        $select = $this->repository->select();
        if (!empty($selectScope)) {
            /** @psalm-suppress MixedMethodCall */
            $select->where($selectScope);
        }

        $input = new ArrayInput($arrayInput);
        $factory = $this->gridFactory->withInput($input);
        $result = $factory->create($select, $schema);

        /** @var array<string,array|null|string> $values */
        $values = [];
        foreach ([
                     GridInterface::FILTERS,
                     GridInterface::SORTERS,
                     GridInterface::COUNT,
                     GridInterface::PAGINATOR
                 ] as $key) {
            $option = $result->getOption($key) ?? [];
            $values[$key] = $option;
        }
        $filters = $values[GridInterface::FILTERS] ?? [];
        $page = $values[GridInterface::PAGINATOR]['page'] ?? 1;
        $limit = $values[GridInterface::PAGINATOR]['limit'] ?? Pagination::DEFAULT_LIMIT;
        $total = !empty($values[GridInterface::COUNT]) ? (int)$values[GridInterface::COUNT] : 0;
        /** @var string|null $order
         * @psalm-suppress MixedArgument
         * */
        $order = array_key_first($values[GridInterface::SORTERS]) ?? null;
        $orderDirection = null;
        if (!is_null($order)) {
            $orderDirection = $values[GridInterface::SORTERS][$order] ?? Sort::ASC;
        }


        /** @var array<TEntity> $resultIterator */
        $resultIterator = iterator_to_array($result->getIterator());
        $dtos = array_map(
        /** @param TEntity $entity */
            fn ($entity) => $this->map($entity), $resultIterator);

        return new QueryListResponseWrapper(
            $dtos,
            $total,
            (int)$page,
            (int)$limit,
            (array)$filters,
            $order,
            (string)$orderDirection
        );
    }

    /**
     * @param TEntity $entity
     *
     * @return TDto
     */
    abstract protected function map($entity);
}
