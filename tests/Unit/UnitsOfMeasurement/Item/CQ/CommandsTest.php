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
use Modules\UnitsOfMeasurement\Domain\Entity\Item;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\ValueObject\Sort;
use SharedCQRS\ListQueryResponseWrapper;
use SharedCQRS\UnitsOfMeasurement\Command\UnitDeleteCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitPatchCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitSaveCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitSetDefaultCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitsResortCommand;
use SharedCQRS\UnitsOfMeasurement\Command\UnitsRestoreCommand;
use SharedCQRS\UnitsOfMeasurement\Query\UnitGetQuery;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListInTrashQuery;
use SharedCQRS\UnitsOfMeasurement\Query\UnitsListQuery;
use SharedCQRS\UnitsOfMeasurement\UnitDTO;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Cqrs\QueryBusInterface;
use Tests\BaseTestCase;
use Throwable;

class CommandsTest extends BaseTestCase
{
    /**
     * @var CommandBusInterface
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private CommandBusInterface $commandBus;
    /**
     * @var QueryBusInterface
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private QueryBusInterface $queryBus;

    public function test_insert(): void
    {
        /// Create test
        $command = new UnitSaveCommand(
            id: null,
            name: 'Test',
            precision: 2,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUIDs are not in the correct');
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        $this->assertIsObject($dto);
    }

    public function test_put(): void
    {
        /// Create test
        $command = new UnitSaveCommand(
            id: null,
            name: 'Test',
            precision: 2,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /// Update test
        $command = new UnitSaveCommand(
            id: $uuid,
            name: 'Test',
            precision: 2,
        );
        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var UnitDTO $dto */
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        $this->assertInstanceOf(UnitDTO::class, $dto, 'Response is not instance DTO');
    }

    public function test_delete(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        /// Create
        $command = new UnitSaveCommand(
            id: null,
            name: 'Test',
            precision: 2,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var bool $result */
        $result = $this->commandBus->dispatch(new UnitDeleteCommand($uuid));
        $this->assertTrue($result, 'Delete command failed');
        $this->queryBus->ask(new UnitGetQuery($uuid));
    }

    public function test_update(): void
    {
        /// Create test
        $command = new UnitSaveCommand(
            id: null,
            name: 'Test',
            precision: 2,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        // Get saved item
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        $this->assertInstanceOf(UnitDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test', $dto->name, 'Name not saved');
        $this->assertEquals(2, $dto->precision, 'Precision not saved');


        $command = new UnitPatchCommand(
            id: $uuid,
            name: 'Test update',
        );
        /** @var bool $result */
        $result = $this->commandBus->dispatch($command);

        $this->assertTrue($result, 'Update command failed');
        // Get updated item
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        $this->assertInstanceOf(UnitDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test update', $dto->name, 'Name not updated');
        $this->assertEquals(2, $dto->precision, 'Precision not updated');

        /// Update all fields
        $command = new UnitPatchCommand(
            id: $uuid,
            name: 'Test update 2',
            precision: 105
        );
        $this->commandBus->dispatch($command);
        // Get updated item
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        // Check DTO
        $this->assertInstanceOf(UnitDTO::class, $dto, 'Response is not instance DTO');
        // fields
        $this->assertEquals('Test update 2', $dto->name, 'Name not updated');
        $this->assertEquals(105, $dto->precision, 'Precision not updated');
    }

    public function test_resort(): void
    {
        $factory = DBItemFactory::new();
        /* @var Item[] $items */
        $items = $factory->times(5)->create();

        /* @var Uuid[] $uuids */
        $uuids = [];
        foreach ($items as $item) {
            $uuids[] = $item->getId();
        }

        // Shuffle the UUIDs to simulate reordering
        shuffle($uuids);


        // Create and dispatch the command
        $result = $this->commandBus->dispatch(new UnitsResortCommand($uuids));

        $this->assertTrue($result, 'Resort command failed');

        // Verify the sorting
        /**@var ListQueryResponseWrapper<UnitDTO> $sortedDTOs */
        $sortedDTOs = $this->queryBus->ask(new UnitsListQuery(
                order: Item::F_SORT,
                orderDirection: Sort::ASC,
            )
        );
        foreach ($sortedDTOs->items as $index => $sortedDTO) {
            $this->assertEquals(($uuids[$index])->getValue(), $sortedDTO->id->getValue(), 'UUIDs are not in the correct order');
        }
    }

    public function test_restore_items(): void
    {
        $factory = DBItemFactory::new();
        /** @var Item[] $items */
        $items = $factory->deleted()->times(10)->create();
        /** @var Uuid[] $uuids */
        $uuids = [];
        foreach ($items as $item) {
            $uuids[] = $item->getId();
        }

        $result = $this->commandBus->dispatch(new UnitsRestoreCommand($uuids));
        $this->assertTrue($result, 'Restore command failed');

        $dto = $this->queryBus->ask(new UnitsListInTrashQuery());
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(0, $dto->items, 'Items not restored');
    }

    public function test_set_default(): void
    {
        $factory = DBItemFactory::new();
        /** @var Item[] $items */
        $items = $factory->times(10)->create();

        // Set default
        $uuid = $items[0]->getId();
        $this->commandBus->dispatch(new UnitSetDefaultCommand($uuid));
        // Check default
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid));
        $this->assertInstanceOf(UnitDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Set another default
        $uuid2 = $items[1]->getId();
        $this->commandBus->dispatch(new UnitSetDefaultCommand($uuid2));
        $dto = $this->queryBus->ask(new UnitGetQuery($uuid2));
        $this->assertInstanceOf(UnitDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Check old default
        $oldDefault = $this->queryBus->ask(new UnitGetQuery($items[0]->getId()));
        $this->assertInstanceOf(UnitDTO::class, $oldDefault);
        $this->assertFalse($oldDefault->isDefault, 'Old default item not unset');
    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();
        /**@var CommandBusInterface $commandBus */
        $this->commandBus = $this->getContainer()->get(CommandBusInterface::class);
        /**@var QueryBusInterface $queryBus */
        $this->queryBus = $this->getContainer()->get(QueryBusInterface::class);
    }
}
