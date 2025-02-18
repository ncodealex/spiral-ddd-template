<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\Brand\Item\CQ;

use Database\Factory\Brand\DBItemFactory;
use Modules\Brand\Domain\Entity\Item;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Domain\ValueObject\Brand\BrandOwner;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\ValueObject\Sort;
use SharedCQRS\Brand\BrandDTO;
use SharedCQRS\Brand\Command\BrandDeleteCommand;
use SharedCQRS\Brand\Command\BrandPatchCommand;
use SharedCQRS\Brand\Command\BrandSaveCommand;
use SharedCQRS\Brand\Command\BrandSetDefaultCommand;
use SharedCQRS\Brand\Command\BrandsResortCommand;
use SharedCQRS\Brand\Command\BrandsRestoreCommand;
use SharedCQRS\Brand\Query\BrandGetQuery;
use SharedCQRS\Brand\Query\BrandListInTrashQuery;
use SharedCQRS\Brand\Query\BrandListQuery;
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
        $command = new BrandSaveCommand(
            id: null,
            owner: BrandOwner::SHARE,
            name: Name::create('Test name'),
            comment: 'Test comment',
            isDefault: false,
            skuProperty: 'SKU123',
            country: 'Country',
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUIDs are not in the correct');
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        $this->assertIsObject($dto);
    }

    public function test_put(): void
    {
        /// Create test
        $command = new BrandSaveCommand(
            id: null,
            owner: BrandOwner::SHARE,
            name: Name::create('Test name'),
            comment: 'Test comment',
            isDefault: false,
            skuProperty: 'SKU123',
            country: 'Country',
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /// Update test
        $command = new BrandSaveCommand(
            id: $uuid,
            owner: BrandOwner::SHARE,
            name: Name::create('Test name updated'),
            comment: 'Test comment updated',
            isDefault: true,
            skuProperty: 'SKU123456',
            country: 'Country2',
            sort: 1
        /**
         *  Entity properties
         **/
        );
        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var BrandDTO $dto */
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        $this->assertInstanceOf(BrandDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test name updated', $dto->name->getValue(), 'Item name not updated');
        $this->assertEquals('Test comment updated', $dto->comment, 'Item comment not updated');
        $this->assertTrue($dto->isDefault, 'Item isDefault not updated');
        $this->assertEquals('SKU123456', $dto->skuProperty, 'Item skuProperty not updated');
    }

    public function test_delete(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        /// Create
        $command = new BrandSaveCommand(
            id: null,
            owner: BrandOwner::SHARE,
            name: Name::create('Test name'),
            comment: 'Test comment',
            isDefault: false,
            skuProperty: 'SKU123',
            country: 'Country',
            sort: 1
        /**
         *  Entity properties
         **/
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        /** @var bool $result */
        $result = $this->commandBus->dispatch(new BrandDeleteCommand($uuid));
        $this->assertTrue($result, 'Delete command failed');
        $this->queryBus->ask(new BrandGetQuery($uuid));
    }

    public function test_update(): void
    {
        /// Create test
        $command = new BrandSaveCommand(
            id: null,
            owner: BrandOwner::SHARE,
            name: Name::create('Test name'),
            comment: 'Test comment',
            isDefault: false,
            skuProperty: 'SKU123',
            country: 'Country',
            sort: 1
        );

        $uuid = $this->commandBus->dispatch($command);
        $this->assertInstanceOf(Uuid::class, $uuid, 'UUID are not correct');
        // Get saved item
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        $this->assertInstanceOf(BrandDTO::class, $dto, 'Response is not instance DTO');
        // Update only maxPower
        $command = new BrandPatchCommand(
            id: $uuid,
            name: Name::create('Test updated')
        );
        /** @var bool $result */
        $result = $this->commandBus->dispatch($command);

        $this->assertTrue($result, 'Update command failed');
        // Get updated item
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        $this->assertInstanceOf(BrandDTO::class, $dto, 'Response is not instance DTO');
        $this->assertEquals('Test updated', $dto->name, 'Item name not updated');

        /// Update all fields
        $command = new BrandPatchCommand(
            id: $uuid,
            name: Name::create('NAME'),
            comment: 'COMMENT',
            skuProperty: 'SKU',
            country: 'COUNTRY',
            sort: 500
        );
        $this->commandBus->dispatch($command);
        // Get updated item
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        // Check DTO
        $this->assertInstanceOf(BrandDTO::class, $dto, 'Response is not instance DTO');
        // fields
        $this->assertEquals('NAME', $dto->name->getValue(), 'Item name not updated');
        $this->assertEquals('COMMENT', $dto->comment, 'Item comment not updated');
        $this->assertEquals('SKU', $dto->skuProperty, 'Item skuProperty not updated');
        $this->assertEquals('COUNTRY', $dto->country, 'Item country not updated');
        $this->assertEquals(500, $dto->sort, 'Item sort not updated');
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
        $result = $this->commandBus->dispatch(new BrandsResortCommand($uuids));

        $this->assertTrue($result, 'Resort command failed');

        // Verify the sorting
        /**@var ListQueryResponseWrapper<BrandDTO> $sortedDTOs */
        $sortedDTOs = $this->queryBus->ask(new BrandListQuery(
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

        $result = $this->commandBus->dispatch(new BrandsRestoreCommand($uuids));
        $this->assertTrue($result, 'Restore command failed');

        $dto = $this->queryBus->ask(new BrandListInTrashQuery());
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
        $this->commandBus->dispatch(new BrandSetDefaultCommand($uuid, BrandOwner::SHARE));
        // Check default
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid));
        $this->assertInstanceOf(BrandDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Set another default
        $uuid2 = $items[1]->getId();
        $this->commandBus->dispatch(new BrandSetDefaultCommand($uuid2, BrandOwner::SHARE));
        $dto = $this->queryBus->ask(new BrandGetQuery($uuid2));
        $this->assertInstanceOf(BrandDTO::class, $dto);
        $this->assertTrue($dto->isDefault, 'Item not set as default');
        // Check old default
        $oldDefault = $this->queryBus->ask(new BrandGetQuery($items[0]->getId()));
        $this->assertInstanceOf(BrandDTO::class, $oldDefault);
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
