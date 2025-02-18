<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\WorkLocations\WorkLocations\CQ;

use Database\Factory\WorkLocation\DBWorkLocationFactory;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\WorkLocation\Query\ListWorkLocationQuery;
use Spiral\Cqrs\QueryBusInterface;
use Tests\BaseTestCase;

class QueryTest extends BaseTestCase
{
    private QueryBusInterface $queryBus;

    public function test_list(): void
    {

        $factory = DBWorkLocationFactory::new();

        $factory->times(30)->create();

        $dto = $this->queryBus->ask(new ListWorkLocationQuery());

        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(30, $dto->items);

    }

    public function test_trashed(): void
    {

        $factory = DBWorkLocationFactory::new();
        // Create 30 work locations
        $factory->times(30)->create();
        // get all work locations
        $dto = $this->queryBus->ask(new ListWorkLocationQuery());
        // check if all work locations are returned
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        // check if the count of work locations is 30
        $this->assertCount(30, $dto->items);
        // delete 7 work locations
        $factory->deleted()->times(7)->create();
        // get all work locations
        $dto = $this->queryBus->ask(new ListWorkLocationQuery(withTrash: true));
        // check if all work locations are returned
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);

        $this->assertCount(7, $dto->items);

    }

    protected function setUp(): void
    {
        parent::setUp();
        /**@var QueryBusInterface $queryBus */
        $this->queryBus = $this->getContainer()->get(QueryBusInterface::class);
    }
}
