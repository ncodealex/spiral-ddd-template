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
use Modules\Category\Application\Schema\Item\ListQueryHandlerSchema;
use Modules\Category\Domain\Entity\Item;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Application\Query\AbstractListQueryHandler;
use Shared\Domain\Exception\BadLogicException;
use Shared\Infrastructure\ValueObject\Pagination;
use SharedCQRS\Category\CategoryDTO;
use SharedCQRS\Category\Query\CategoriesListQuery;
use SharedCQRS\ListQueryResponseWrapper;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\DataGrid\GridFactory;

/**
 * @extends AbstractListQueryHandler<Item, CategoryDTO>
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
     * @param CategoriesListQuery $query
     *
     * @return ListQueryResponseWrapper<CategoryDTO>
     * @throws Exception
     */
    #[QueryHandler]
    public function __invoke(CategoriesListQuery $query): ListQueryResponseWrapper
    {
        $scope = [];
        if (!$query->withTrash) {
            $scope = [
                Item::F_DELETED_AT => null
            ];
        }
        return $this->handleCommand($this->schema, $this->mapToArrayInput($query), $scope);

    }

    protected function mapToArrayInput(CategoriesListQuery $query): array
    {
        return [
            GridFactory::KEY_FILTER => array_filter([
                Item::F_OWNER => $query->owner->getValue(),
                Item::F_NAME => $query->search,
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

    protected function map(mixed $entity): CategoryDTO
    {
        if (!$entity instanceof Item) {
            throw new BadLogicException('Invalid entity');
        }
        return ItemMapper::toDTO($entity);
    }
}
