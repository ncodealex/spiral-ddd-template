<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Group\Application\Query\Item;

use Modules\Group\Application\Mapper\ItemMapper;
use Modules\Group\Domain\Exception\Item\ItemNotFoundException;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use SharedCQRS\Group\GroupDTO;
use SharedCQRS\Group\Query\GroupGetQuery;
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
     * @param GroupGetQuery $query
     *
     * @return GroupDTO|null
     * @throws ItemNotFoundException
     */
    #[QueryHandler]
    public function __invoke(GroupGetQuery $query): ?GroupDTO
    {
        $entity = $this->repository->getByPk($query->id, $query->inTrash);
        return ItemMapper::toDTO($entity);
    }

}
