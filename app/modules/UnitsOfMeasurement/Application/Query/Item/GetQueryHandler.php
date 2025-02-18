<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Query\Item;

use Modules\UnitsOfMeasurement\Application\Mapper\ItemMapper;
use Modules\UnitsOfMeasurement\Domain\Exception\Item\ItemNotFoundException;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\UnitsOfMeasurement\Query\UnitGetQuery;
use SharedCQRS\UnitsOfMeasurement\UnitDTO;
use Spiral\Cqrs\Attribute\QueryHandler;

final readonly class GetQueryHandler
{

    public function __construct(
        private ItemRepositoryInterface $repository
    )
    {
    }

    /**
     *  Get the entity
     *
     * @param UnitGetQuery $query
     *
     * @return UnitDTO|null
     * @throws ItemNotFoundException
     */
    #[QueryHandler]
    public function __invoke(UnitGetQuery $query): ?UnitDTO
    {
        $entity = $this->repository->getByPk($query->id, $query->inTrash);
        return ItemMapper::toDTO($entity);
    }

}
