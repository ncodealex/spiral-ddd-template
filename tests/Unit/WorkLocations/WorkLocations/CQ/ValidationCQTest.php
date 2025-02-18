<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\WorkLocations\WorkLocations\CQ;

use Shared\Domain\Exception\AssertException;
use Shared\Domain\Exception\ResourceNotFoundException;
use Shared\Domain\ValueObject\Uuid;
use SharedCQRS\WorkLocation\Command\DeleteWorkLocationCommand;
use SharedCQRS\WorkLocation\Command\RestoreWorkLocationCommand;
use SharedCQRS\WorkLocation\Command\SaveWorkLocationCommand;
use SharedCQRS\WorkLocation\Query\GetWorkLocationQuery;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Cqrs\QueryBusInterface;
use Tests\BaseTestCase;
use Throwable;

class ValidationCQTest extends BaseTestCase
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    /**
     *  Save assert uuid
     */
    public function test_assert_uuid_save(): void
    {
        $this->expectException(AssertException::class);
        $command = new SaveWorkLocationCommand(
            id: '123',/// Not uuid value
            name: 'Test',
            floor: 1,
            maxPower: 100,
            blackList: false,
            sort: 1
        );
        $this->commandBus->dispatch($command);
    }

    /**
     *  Save not found exception Assertion
     */
    public function test_not_found_save(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        /// Update test
        $command = new SaveWorkLocationCommand(
            id: UUid::generate()->getValue(),
            name: 'Test3',
            floor: 3,
            maxPower: 300,
            blackList: false,
            sort: 3
        );
        $this->commandBus->dispatch($command);
    }

    /**
     *  Get assert uuid
     */
    public function test_assert_uuid_get(): void
    {
        $this->expectException(AssertException::class);
        $this->queryBus->ask(new GetWorkLocationQuery(
            '123' /// No valid uuid
        ));
    }

    /**
     *  Get not found exception Assertion
     */
    public function test_not_found_get(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->queryBus->ask(new GetWorkLocationQuery(
            UUid::generate()->getValue()
        ));
    }

    /**
     *  Delete assert uuid
     */
    public function test_assert_uuid_delete(): void
    {
        $this->expectException(AssertException::class);
        $command = new DeleteWorkLocationCommand(
            id: '123',/// Not uuid value
        );
        $this->commandBus->dispatch($command);
    }

    /**
     *  Delete not found exception Assertion
     */
    public function test_not_found_delete(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $command = new DeleteWorkLocationCommand(
            id: UUid::generate()->getValue(),/// Not uuid value
        );
        $this->commandBus->dispatch($command);
    }


    /**
     *  Restore assert uuid
     */
    public function test_assert_uuid_restore(): void
    {
        $this->expectException(AssertException::class);
        $command = new RestoreWorkLocationCommand(
            id: '123',/// Not uuid value
        );
        $this->commandBus->dispatch($command);
    }

    /**
     *  Restore not found exception Assertion
     */
    public function test_not_found_restore(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $command = new RestoreWorkLocationCommand(
            id: UUid::generate()->getValue()/// Not uuid value
        );
        $this->commandBus->dispatch($command);
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
