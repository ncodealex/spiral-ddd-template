<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\Group\Item\CQ;

use Database\Factory\Group\DBItemFactory;
use Modules\Group\Domain\Entity\Item;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Group\GroupOwner;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\ValueObject\Sort;
use SharedCQRS\Group\Command\GroupDeleteCommand;
use SharedCQRS\Group\Command\GroupPatchCommand;
use SharedCQRS\Group\Command\GroupSaveCommand;
use SharedCQRS\Group\Command\GroupSetDefaultCommand;
use SharedCQRS\Group\Command\GroupsResortCommand;
use SharedCQRS\Group\Command\GroupsRestoreCommand;
use SharedCQRS\Group\GroupDTO;
use SharedCQRS\Group\Query\GroupGetQuery;
use SharedCQRS\Group\Query\GroupsListInTrashQuery;
use SharedCQRS\Group\Query\GroupsListQuery;
use SharedCQRS\ListQueryResponseWrapper;
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
        $command = new GroupSaveCommand(
            id: null,
            owner: GroupOwner::DEFAULT,
            name: Name::create('Test'),
            color: Color::create(Item::DEFAULT_COLOR),
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUIDs are not in the correct');
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        $this->assertIsObject($dto);
    }

    public function test_put(): void
    {
        /// Create test
        $command = new GroupSaveCommand(
            id: null,
            owner: GroupOwner::DEFAULT,
            name: Name::create('Test')
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /// Update test
        $command = new GroupSaveCommand(
            id: $uuid,
            owner: GroupOwner::DEFAULT,
            name: Name::create('Test update'),
        );
        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var GroupDTO $dto */
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        $this->assertInstanceOf(GroupDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test update', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals(GroupOwner::DEFAULT->getValue(), $dto->owner->getValue(), 'Owner is not correct');
    }

    public function test_delete(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        /// Create
        $command = new GroupSaveCommand(
            id: null,
            owner: GroupOwner::DEFAULT,
            name: Name::create('Test'),
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var bool $result */
        $result = $this->commandBus->dispatch(new GroupDeleteCommand($uuid));
        $this->assertTrue($result, 'Delete command failed');
        $this->queryBus->ask(new GroupGetQuery($uuid));
    }

    public function test_update(): void
    {
        /// Create test
        $command = new GroupSaveCommand(
            id: null,
            owner: GroupOwner::DEFAULT,
            name: Name::create('Test'),
            color: Color::create(Item::DEFAULT_COLOR),
            comment: 'Test comment',
            placeholder: 'Test placeholder',
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        // Get saved item
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        $this->assertInstanceOf(GroupDTO::class, $dto, 'Response is not instance DTO');
        $command = new GroupPatchCommand(
            id: $uuid,
            comment: 'Test comment update'
        );
        /** @var bool $result */
        $result = $this->commandBus->dispatch($command);

        $this->assertTrue($result, 'Update command failed');
        // Get updated item
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        $this->assertInstanceOf(GroupDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test comment update', $dto->comment, 'Comment is not correct');

        /// Update all fields
        $command = new GroupPatchCommand(
            id: $uuid,
            name: Name::create('Test update'),
            color: Color::create('#f8f8f8'),
            comment: 'Test comment update',
            placeholder: 'Test placeholder update',
            sort: 22,
        );
        $this->commandBus->dispatch($command);
        // Get updated item
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        // Check DTO
        $this->assertInstanceOf(GroupDTO::class, $dto, 'Response is not instance DTO');
        // fields
        $this->assertEquals('Test update', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals(GroupOwner::DEFAULT->getValue(), $dto->owner->getValue(), 'Owner is not correct');
        $this->assertEquals('#f8f8f8', $dto->color->getValue(), 'Color is not correct');
        $this->assertEquals('Test comment update', $dto->comment, 'Comment is not correct');
        $this->assertEquals('Test placeholder update', $dto->placeholder, 'Placeholder is not correct');
        $this->assertEquals(22, $dto->sort, 'Sort is not correct');
    }

    public function test_resort(): void
    {
        $factory = DBItemFactory::new();
        // Create 30 work locations
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
        $result = $this->commandBus->dispatch(new GroupsResortCommand($uuids));

        $this->assertTrue($result, 'Resort command failed');

        // Verify the sorting
        /**@var ListQueryResponseWrapper<GroupDTO> $sortedDTOs */
        $sortedDTOs = $this->queryBus->ask(new GroupsListQuery(
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

        $result = $this->commandBus->dispatch(new GroupsRestoreCommand($uuids));
        $this->assertTrue($result, 'Restore command failed');

        $dto = $this->queryBus->ask(new GroupsListInTrashQuery());
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        var_dump($dto->items);
        $this->assertCount(0, $dto->items, 'Items not restored');
    }

    public function test_set_default(): void
    {
        $factory = DBItemFactory::new();
        /** @var Item[] $items */
        $items = $factory->times(10)->create();

        // Set default
        $uuid = $items[0]->getId();
        $this->commandBus->dispatch(new GroupSetDefaultCommand($uuid, GroupOwner::DEFAULT));
        // Check default
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid));
        $this->assertInstanceOf(GroupDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Set another default
        $uuid2 = $items[1]->getId();
        $this->commandBus->dispatch(new GroupSetDefaultCommand($uuid2, GroupOwner::DEFAULT));
        $dto = $this->queryBus->ask(new GroupGetQuery($uuid2));
        $this->assertInstanceOf(GroupDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Check old default
        $oldDefault = $this->queryBus->ask(new GroupGetQuery($items[0]->getId()));
        $this->assertInstanceOf(GroupDTO::class, $oldDefault);
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
