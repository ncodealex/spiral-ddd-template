<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Query\Item;

use Modules\Category\Application\Mapper\ItemTreeMapper;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Category\CategoryTreeDTO;
use SharedCQRS\Category\Query\CategoryGetWithChildrenQuery;
use Spiral\Cqrs\Attribute\QueryHandler;

final readonly class GetItemTreeQueryHandler
{

    public function __construct(
        private ItemRepositoryInterface $repository
    )
    {
    }

    /**
     *  Get the entity
     *
     * @param CategoryGetWithChildrenQuery $query
     *
     * @return CategoryTreeDTO
     */
    #[QueryHandler]
    public function __invoke(CategoryGetWithChildrenQuery $query): CategoryTreeDTO
    {
        $entity = $this->repository->getByPkIncludeChildren($query->id, $query->inTrash);
        return ItemTreeMapper::toDTO($entity);
    }

}
