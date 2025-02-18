<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\UnitsOfMeasurement\Item\CQ;

use Database\Factory\UnitsOfMeasurement\DBItemFactory;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListInTrashQuery;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListQuery;
use Spiral\Cqrs\QueryBusInterface;
use Tests\BaseTestCase;
use Throwable;

class QueryTest extends BaseTestCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private QueryBusInterface $queryBus;

    public function test_list(): void
    {

        $factory = DBItemFactory::new();

        $factory->times(30)->create();

        $dto = $this->queryBus->ask(new UnitsListQuery());

        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(30, $dto->items);

    }

    public function test_list_with_trashed(): void
    {
        $factory = DBItemFactory::new();
        // Create 30 work locations
        $factory->times(30)->create();
        // get all work locations
        $dto = $this->queryBus->ask(new UnitsListQuery());
        // check if all are returned
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        // check if the count is 30
        $this->assertCount(30, $dto->items);
        // delete 7 work locations
        $factory->deleted()->times(7)->create();
        // get all work locations
        $dto = $this->queryBus->ask(new UnitsListQuery(withTrash: true));
        // check if all work locations are returned
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(37, $dto->items);

    }


    public function test_trash_list(): void
    {
        $factory = DBItemFactory::new();
        $factory->times(30)->create();
        // delete 7 work locations
        $factory->deleted()->times(7)->create();
        // get all work locations
        $dto = $this->queryBus->ask(new UnitsListInTrashQuery());
        // check if all work locations are returned
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(7, $dto->items);

    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();
        /**@var QueryBusInterface $queryBus */
        $this->queryBus = $this->getContainer()->get(QueryBusInterface::class);
    }
}
