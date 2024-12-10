<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Group\Application\Query\Item;

use Exception;
use Modules\Group\Application\Mapper\ItemMapper;
use Modules\Group\Application\Schema\Item\TrashListQueryHandlerSchema;
use Modules\Group\Domain\Entity\Item;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use Shared\Application\Query\AbstractListQueryHandler;
use Shared\Domain\Exception\BadLogicException;
use Shared\Infrastructure\ValueObject\Pagination;
use SharedCQRS\Group\GroupDTO;
use SharedCQRS\Group\Query\GroupsListInTrashQuery;
use SharedCQRS\ListQueryResponseWrapper;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\DataGrid\GridFactory;

/**
 * @extends AbstractListQueryHandler<Item, GroupDTO>
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
     * @param GroupsListInTrashQuery $query
     *
     * @return ListQueryResponseWrapper<GroupDTO>
     * @throws Exception
     */
    #[QueryHandler]
    public function __invoke(GroupsListInTrashQuery $query): ListQueryResponseWrapper
    {
        $scope = [
            Item::F_DELETED_AT => ['!=' => null]
        ];
        return $this->handleCommand($this->schema, $this->mapToArrayInput($query), $scope);

    }

    protected function mapToArrayInput(GroupsListInTrashQuery $query): array
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

    protected function map(mixed $entity): GroupDTO
    {
        if (!$entity instanceof Item) {
            throw new BadLogicException('Invalid entity');
        }
        return ItemMapper::toDTO($entity);
    }
}
