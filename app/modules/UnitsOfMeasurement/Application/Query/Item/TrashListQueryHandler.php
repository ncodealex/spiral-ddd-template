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
use Modules\UnitsOfMeasurement\Application\Schema\Item\TrashListQueryHandlerSchema;
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Shared\Application\Query\AbstractListQueryHandler;
use Shared\Domain\Exception\BadLogicException;
use Shared\Infrastructure\ValueObject\Pagination;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListInTrashQuery;
use SharedCQRS\UnitsOfMeasurement\UnitDTO;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\DataGrid\GridFactory;

/**
 * @extends AbstractListQueryHandler<Item, UnitDTO>
 */
final readonly class TrashListQueryHandler extends AbstractListQueryHandler
{

    public function __construct(
        protected TrashListQueryHandlerSchema $schema,
        GridFactory                           $gridFactory,
        ItemRepositoryInterface               $repository,
    )
    {
        parent::__construct($gridFactory, $repository);
    }

    /**
     * Get the list  entity
     *
     * @param UnitsListInTrashQuery $query
     *
     * @return ListQueryResponseWrapper<UnitDTO>
     * @throws Exception
     */
    #[QueryHandler]
    public function __invoke(UnitsListInTrashQuery $query): ListQueryResponseWrapper
    {
        $scope = [
            Item::F_DELETED_AT => ['!=' => null]
        ];
        return $this->handleCommand($this->schema, $this->mapToArrayInput($query), $scope);

    }

    protected function mapToArrayInput(UnitsListInTrashQuery $query): array
    {
        return [
            GridFactory::KEY_FILTER => [],
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
