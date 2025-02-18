<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Query\Item;

use Modules\Category\Application\Mapper\ItemMapper;
use Modules\Category\Domain\Exception\Item\ItemNotFoundException;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Category\CategoryDTO;
use SharedCQRS\Category\Query\CategoryGetQuery;
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
     * @param CategoryGetQuery $query
     *
     * @return CategoryDTO|null
     * @throws ItemNotFoundException
     */
    #[QueryHandler]
    public function __invoke(CategoryGetQuery $query): ?CategoryDTO
    {
        $entity = $this->repository->getByPk($query->id, $query->inTrash);
        return ItemMapper::toDTO($entity);
    }

}
