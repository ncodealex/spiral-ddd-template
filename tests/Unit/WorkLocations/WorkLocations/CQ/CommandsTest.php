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
use Modules\WorkLocation\Domain\Entity\WorkLocation;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Infrastructure\ValueObject\Sort;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\WorkLocation\Command\DeleteWorkLocationCommand;
use SharedCQRS\WorkLocation\Command\ResortWorkLocationsCommand;
use SharedCQRS\WorkLocation\Command\SaveWorkLocationCommand;
use SharedCQRS\WorkLocation\Command\UpdateWorkLocationCommand;
use SharedCQRS\WorkLocation\Query\GetWorkLocationQuery;
use SharedCQRS\WorkLocation\Query\ListWorkLocationQuery;
use SharedCQRS\WorkLocation\WorkLocationDTO;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Cqrs\QueryBusInterface;
use Tests\BaseTestCase;

class CommandsTest extends BaseTestCase
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function test_insert(): void
    {
        /// Create test
        $command = new SaveWorkLocationCommand(
            id: null,
            name: 'Test',
            floor: 1,
            maxPower: 100,
            blackList: false,
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertIsString($uuid);
        $dto = $this->queryBus->ask(new GetWorkLocationQuery($uuid));
        $this->assertIsObject($dto);
        $this->assertEquals('Test', $dto->name);
        $this->assertEquals(100, $dto->maxPower);
    }

    public function test_put(): void
    {
        /// Create test
        $command = new SaveWorkLocationCommand(
            id: null,
            name: 'Test',
            floor: 1,
            maxPower: 100,
            blackList: false,
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        /// Update test
        $command = new SaveWorkLocationCommand(
            id: $uuid,
            name: 'Test3',
            floor: 3,
            maxPower: 300,
            blackList: false,
            sort: 3
        );
        $uuid = $this->commandBus->dispatch($command);
        $dto = $this->queryBus->ask(new GetWorkLocationQuery($uuid));
        $this->assertIsObject($dto);
        $this->assertEquals('Test3', $dto->name);
        $this->assertEquals(300, $dto->maxPower);
    }

    public function test_delete(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        /// Create
        $command = new SaveWorkLocationCommand(
            id: null,
            name: 'Test',
            floor: 1,
            maxPower: 100,
            blackList: false,
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        /** @var bool $result */
        $result = $this->commandBus->dispatch(new DeleteWorkLocationCommand($uuid));

        $this->assertTrue($result, 'Delete command failed');

        $this->queryBus->ask(new GetWorkLocationQuery($uuid));
    }

    public function test_update(): void
    {
        /// Create test
        $command = new SaveWorkLocationCommand(
            id: null,
            name: 'Test',
            floor: 1,
            maxPower: 450,
            blackList: false,
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        // Get saved item
        $dto = $this->queryBus->ask(new GetWorkLocationQuery($uuid));
        $this->assertIsObject($dto);
        $this->assertEquals('Test', $dto->name);
        $this->assertEquals(450, $dto->maxPower);
        // Update only maxPower
        $command = new UpdateWorkLocationCommand(
            id: $uuid,
            maxPower: 500
        );
        $this->commandBus->dispatch($command);
        // Get updated item
        $dto = $this->queryBus->ask(new GetWorkLocationQuery($uuid));
        $this->assertIsObject($dto);
        $this->assertEquals(500, $dto->maxPower);

        /// Update all fields
        $command = new UpdateWorkLocationCommand(
            id: $uuid,
            name: 'Test2',
            floor: 2,
            maxPower: 600,
            blackList: true,
            blackListComment: 'Test comment',
            comment: 'Test comment',
            sort: 2
        );
        $this->commandBus->dispatch($command);
        // Get updated item
        $dto = $this->queryBus->ask(new GetWorkLocationQuery($uuid));
        $this->assertIsObject($dto);
        $this->assertEquals('Test2', $dto->name);
        $this->assertEquals(600, $dto->maxPower);
        $this->assertEquals(2, $dto->floor);
        $this->assertEquals(true, $dto->blackList);
        $this->assertEquals('Test comment', $dto->blackListComment);
        $this->assertEquals('Test comment', $dto->comment);
        $this->assertEquals(2, $dto->sort);
    }

    public function test_resort(): void
    {
        $factory = DBWorkLocationFactory::new();
        // Create 30 work locations
        $items = $factory->times(5)->create();

        /* @var array<array-key,string> $uuids */
        $uuids = [];
        foreach ($items as $item) {
            $uuids[] = $item->getId()->getValue();
        }

        // Shuffle the UUIDs to simulate reordering
        shuffle($uuids);


        // Create and dispatch the command
        $command = new ResortWorkLocationsCommand($uuids);
        $result = $this->commandBus->dispatch($command);

        $this->assertTrue($result, 'Resort command failed');

        // Verify the sorting
        /**@var ListQueryResponseWrapper<WorkLocationDTO> $sortedDTOs */
        $sortedDTOs = $this->queryBus->ask(new ListWorkLocationQuery(
                order: WorkLocation::F_SORT,
                orderDirection: Sort::ASC,
            )
        );
        foreach ($sortedDTOs->items as $index => $sortedDTO) {
            $this->assertEquals($uuids[$index], $sortedDTO->id, 'UUIDs are not in the correct order');
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        /**@var CommandBusInterface $commandBus */
        $this->commandBus = $this->getContainer()->get(CommandBusInterface::class);
        /**@var QueryBusInterface $queryBus */
        $this->queryBus = $this->getContainer()->get(QueryBusInterface::class);
    }
}
