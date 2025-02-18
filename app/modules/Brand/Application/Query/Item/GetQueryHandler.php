<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Brand\Application\Query\Item;

use Modules\Brand\Application\Mapper\ItemMapper;
use Modules\Brand\Domain\Exception\Item\ItemNotFoundException;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Brand\BrandDTO;
use SharedCQRS\Brand\Query\BrandGetQuery;
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
     * @param BrandGetQuery $query
     *
     * @return BrandDTO|null
     * @throws ItemNotFoundException
     */
    #[QueryHandler]
    public function __invoke(BrandGetQuery $query): ?BrandDTO
    {
        $entity = $this->repository->getByPk($query->id, $query->inTrash);
        return ItemMapper::toDTO($entity);
    }

}
