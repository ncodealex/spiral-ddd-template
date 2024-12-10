<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\Category\Application\Query\Item;

use Exception;
use Modules\Category\Application\Mapper\ItemMapper;
use Modules\Category\Application\Schema\Item\TrashListQueryHandlerSchema;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Application\Query\AbstractListQueryHandler;
use Shared\Domain\Exception\BadLogicException;
use Shared\Infrastructure\ValueObject\Pagination;
use SharedCQRS\Category\CategoryDTO;
use SharedCQRS\Category\Query\CategoriesListInTrashQuery;
use SharedCQRS\ListQueryResponseWrapper;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\DataGrid\GridFactory;

/**
 * @extends AbstractListQueryHandler<Item, CategoryDTO>
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
     * @param CategoriesListInTrashQuery $query
     *
     * @return ListQueryResponseWrapper<CategoryDTO>
     * @throws Exception
     */
    #[QueryHandler]
    public function __invoke(CategoriesListInTrashQuery $query): ListQueryResponseWrapper
    {
        $scope = [
            Item::F_DELETED_AT => ['!=' => null]
        ];
        return $this->handleCommand($this->schema, $this->mapToArrayInput($query), $scope);

    }

    protected function mapToArrayInput(CategoriesListInTrashQuery $query): array
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

    protected function map(mixed $entity): CategoryDTO
    {
        if (!$entity instanceof Item) {
            throw new BadLogicException('Invalid entity');
        }
        return ItemMapper::toDTO($entity);
    }
}
