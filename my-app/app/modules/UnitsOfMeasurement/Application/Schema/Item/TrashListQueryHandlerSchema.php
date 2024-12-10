<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Application\Schema\Item;

use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Shared\Application\Schema\AbstractGridSchema;
use Shared\Infrastructure\ValueObject\Pagination;
use Shared\Infrastructure\ValueObject\Sort;
use Spiral\DataGrid\GridFactory;

final class TrashListQueryHandlerSchema extends AbstractGridSchema
{

    public function __construct()
    {
        $this->addSort(Item::F_SORT)
            ->addSort(Item::F_ID);
        $this->addPagination();

    }

    public function getDefaults(): array
    {
        return [
            GridFactory::KEY_SORT => [
                Item::F_SORT => Sort::ASC
            ],
            GridFactory::KEY_PAGINATE => [
                'page' => 1,
                'limit' => Pagination::DEFAULT_LIMIT
            ],
            GridFactory::KEY_FETCH_COUNT => true
        ];
    }

}
