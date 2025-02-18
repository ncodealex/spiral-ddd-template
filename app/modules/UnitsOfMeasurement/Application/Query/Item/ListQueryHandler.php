<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Query\Item;

use Exception;
use Modules\UnitsOfMeasurement\Application\Mapper\ItemMapper;
use Modules\UnitsOfMeasurement\Application\Schema\Item\ListQueryHandlerSchema;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Shared\Application\Query\AbstractListQueryHandler;
use Shared\Domain\Exception\BadLogicException;
use Shared\Infrastructure\ValueObject\Pagination;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListQuery;
use SharedCQRS\UnitsOfMeasurement\UnitDTO;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\DataGrid\GridFactory;

/**
 * @extends AbstractListQueryHandler<Item, UnitDTO>
 */
final readonly class ListQueryHandler extends AbstractListQueryHandler
{

    public function __construct(
        protected ListQueryHandlerSchema $schema,
        GridFactory                      $gridFactory,
        ItemRepositoryInterface          $repository,
    )
    {
        parent::__construct($gridFactory, $repository);
    }

    /**
     * Get the list  entity
     *
     * @param UnitsListQuery $query
     *
     * @return ListQueryResponseWrapper<UnitDTO>
     * @throws Exception
     */
    #[QueryHandler]
    public function __invoke(UnitsListQuery $query): ListQueryResponseWrapper
    {
        $scope = [];
        if (!$query->withTrash) {
            $scope = [
                Item::F_DELETED_AT => null
            ];
        }
        return $this->handleCommand($this->schema, $this->mapToArrayInput($query), $scope);

    }

    protected function mapToArrayInput(UnitsListQuery $query): array
    {
        return [
            GridFactory::KEY_FILTER => array_filter([
                Item::F_NAME => $query->name,
            ], fn ($value) => $value !== null),
            GridFactory::KEY_SORT => array_filter([
                $query->order => $query->orderDirection,
            ], fn ($value) => $value !== null),
            GridFactory::KEY_PAGINATE => array_filter([
                Pagination::LIMIT_NAME => $query->limit,
                Pagination::PAGE_NAME => $query->page,
            ], fn ($value) => $value !== null),
        ];
    }

    protected function map(mixed $entity): UnitDTO
    {
        if (!$entity instanceof Item) {
            throw new BadLogicException('Invalid entity');
        }
        return ItemMapper::toDTO($entity);
    }
}
