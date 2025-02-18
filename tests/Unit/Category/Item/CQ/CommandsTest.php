<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\Category\Item\CQ;

use Database\Factory\Category\DBItemFactory;
use Modules\Category\Domain\Entity\Item;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\ValueObject\Sort;
use SharedCQRS\Brand\Query\BrandListInTrashQuery;
use SharedCQRS\Category\CategoryDTO;
use SharedCQRS\Category\CategoryTreeDTO;
use SharedCQRS\Category\Command\CategoriesResortCommand;
use SharedCQRS\Category\Command\CategoriesRestoreCommand;
use SharedCQRS\Category\Command\CategoryDeleteCommand;
use SharedCQRS\Category\Command\CategoryPatchCommand;
use SharedCQRS\Category\Command\CategoryRestoreCommand;
use SharedCQRS\Category\Command\CategorySaveCommand;
use SharedCQRS\Category\Command\CategorySetDefaultCommand;
use SharedCQRS\Category\Query\CategoriesListQuery;
use SharedCQRS\Category\Query\CategoryGetQuery;
use SharedCQRS\Category\Query\CategoryGetWithChildrenQuery;
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
        $command = new CategorySaveCommand(
            id: null,
            name: Name::create('Test'),
            owner: CategoryOwner::DEFAULT,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUIDs are not in the correct');
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid));
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals(CategoryOwner::DEFAULT->value, $dto->owner->getValue(), 'Owner is not correct');
    }

    public function test_put(): void
    {
        /// Create test
        $command = new CategorySaveCommand(
            id: null,
            name: Name::create('Test'),
            owner: CategoryOwner::DEFAULT,
            comment: 'Comment',
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /// Update test
        $command = new CategorySaveCommand(
            id: $uuid,
            name: Name::create('Test update'),
            owner: CategoryOwner::DEFAULT,
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var CategoryDTO $dto */
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid));
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test update', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals(CategoryOwner::DEFAULT->getValue(), $dto->owner->getValue(), 'Owner is not correct');
        $this->assertNull($dto->comment, 'Comment is not null');
    }

    public function test_delete(): void
    {

        $this->expectException(ResourceNotFoundException::class);

        $factory = DBItemFactory::new();
        $uuid = $factory->createOne()->getId();

        // Delete item
        $result = $this->commandBus->dispatch(new CategoryDeleteCommand($uuid));
        $this->assertTrue($result, 'Delete command failed');

        // Get deleted item
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid, true));
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals($uuid->getValue(), $dto->id->getValue(), 'UUID is not correct');

        // Not found test
        $this->queryBus->ask(new CategoryGetQuery($uuid, false));
    }

    public function test_trash(): void
    {
        $factory = DBItemFactory::new();
        $uuid = $factory->createOne()->getId();

        // Delete item
        $result = $this->commandBus->dispatch(new CategoryDeleteCommand($uuid));
        $this->assertTrue($result, 'Delete command failed');

        // Get deleted item
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid, true));
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals($uuid->getValue(), $dto->id->getValue(), 'UUID is not correct');

        // Restore item
        $result = $this->commandBus->dispatch(new CategoryRestoreCommand($uuid));
        $this->assertTrue($result, 'Restore command failed');

        // Get restored item
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid));
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals($uuid->getValue(), $dto->id->getValue(), 'UUID is not correct');
    }

    public function test_update(): void
    {
        $this->showDatabaseQueries();

        $factory = DBItemFactory::new();
        $item = $factory->createOne();
        $result = $this->commandBus->dispatch(new CategoryPatchCommand(
            id: $item->getId(),
            name: Name::create('Test update'),
        ));
//
        $this->assertTrue($result, 'Update command failed');
        // Update item
        $this->commandBus->dispatch(new CategoryPatchCommand(
            id: $item->getId(),
            name: Name::create('Test update 2'),
            comment: 'Comment 2',
            sort: 2,
        ));
        // Get updated item
        $dto = $this->queryBus->ask(new CategoryGetQuery($item->getId()));
        // Check DTO
        $this->assertInstanceOf(CategoryDTO::class, $dto, 'Response is not instance DTO');
        // fields
        $this->assertEquals('Test update 2', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals('Comment 2', $dto->comment, 'Comment is not correct');
        $this->assertEquals(2, $dto->sort, 'Sort is not correct');
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
        $result = $this->commandBus->dispatch(new CategoriesResortCommand($uuids));

        $this->assertTrue($result, 'Resort command failed');

        // Verify the sorting
        /**@var ListQueryResponseWrapper<CategoryDTO> $sortedDTOs */
        $sortedDTOs = $this->queryBus->ask(new CategoriesListQuery(
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

        $result = $this->commandBus->dispatch(new CategoriesRestoreCommand($uuids));
        $this->assertTrue($result, 'Restore command failed');

        $dto = $this->queryBus->ask(new BrandListInTrashQuery());
        $this->assertInstanceOf(ListQueryResponseWrapper::class, $dto);
        $this->assertCount(0, $dto->items, 'Items not restored');
    }

    public function test_nestable(): void
    {
        $factory = DBItemFactory::new();
        $root = $factory->createOne();
        /** @var Item[] $children */
        $children = $factory->times(5)->create();
        $this->commandBus->dispatch(new CategorySaveCommand(
            id: $root->getId(),
            name: Name::create('Root'),
            owner: CategoryOwner::DEFAULT,
        ));

        $this->showDatabaseQueries();

        // Attach children to the root
        foreach ($children as $child) {
            $result = $this->commandBus->dispatch(new CategorySaveCommand(
                id: $child->getId(),
                name: Name::create('Child'),
                owner: CategoryOwner::DEFAULT,
                parentId: $root->getId(),
            ));
            $this->assertInstanceOf(Uuid::class, $result, 'UUIDs are not in the correct');
        }
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Root', $dto->name->getValue(), 'Name is not correct');
        $this->assertEquals(CategoryOwner::DEFAULT->getValue(), $dto->owner->getValue(), 'Owner is not correct');
        $this->assertCount(5, $dto->children, 'Children not correct');

        /// Check delete child
        $this->commandBus->dispatch(new CategoryDeleteCommand($children[0]->getId()));
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertCount(4, $dto->children, 'Children not correct');

        // Check restore child
        $this->commandBus->dispatch(new CategoryRestoreCommand($children[0]->getId()));
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertCount(5, $dto->children, 'Children not correct');

        // Check move child
        $this->commandBus->dispatch(new CategorySaveCommand(
            id: $children[0]->getId(),
            name: Name::create('Child'),
            owner: CategoryOwner::DEFAULT,
            parentId: $children[1]->getId(),
        ));
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertCount(4, $dto->children, 'Children not correct');
        $this->assertCount(1, $dto->children->get(0)->children, 'Children not correct');

        // Check move child to root
        $this->commandBus->dispatch(new CategorySaveCommand(
            id: $children[0]->getId(),
            name: Name::create('Child'),
            owner: CategoryOwner::DEFAULT,
            parentId: null,
        ));
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertCount(4, $dto->children, 'Children not correct');

        // Check move child to root node
        $this->commandBus->dispatch(new CategorySaveCommand(
            id: $children[0]->getId(),
            name: Name::create('Child'),
            owner: CategoryOwner::DEFAULT,
            parentId: $root->getId(),
        ));
        $dto = $this->queryBus->ask(new CategoryGetWithChildrenQuery($root->getId()));
        $this->assertInstanceOf(CategoryTreeDTO::class, $dto, 'Response is not instance DTO');
        $this->assertCount(5, $dto->children, 'Children not correct');
    }

    public function test_set_default(): void
    {
        $factory = DBItemFactory::new();
        /** @var Item[] $items */
        $items = $factory->times(10)->create();

        // Set default
        $uuid = $items[0]->getId();
        $this->commandBus->dispatch(new CategorySetDefaultCommand($uuid, CategoryOwner::DEFAULT));
        // Check default
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid));
        $this->assertInstanceOf(CategoryDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Set another default
        $uuid2 = $items[1]->getId();
        $this->commandBus->dispatch(new CategorySetDefaultCommand($uuid2, CategoryOwner::DEFAULT));
        $dto = $this->queryBus->ask(new CategoryGetQuery($uuid2));
        $this->assertInstanceOf(CategoryDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Check old default
        $oldDefault = $this->queryBus->ask(new CategoryGetQuery($items[0]->getId()));
        $this->assertInstanceOf(CategoryDTO::class, $oldDefault);
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
