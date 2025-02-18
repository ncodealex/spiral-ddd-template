<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Event\InMemory;

use Psr\EventDispatcher\EventDispatcherInterface;
use Shared\Domain\Bus\Event\DomainEvent;
use Shared\Domain\Bus\Event\DomainEventDispatcherInterface;
use Shared\Domain\Exception\InvalidInputException;

final readonly class InMemoryDomainEventDispatcher implements DomainEventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher
    )
    {
    }

    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            /** @var DomainEvent $event */
            $this->validateEvent($event);
            $this->dispatcher->dispatch($event);
        }
    }

    private function validateEvent(mixed $event): void
    {
        if (!$event instanceof DomainEvent) {
            throw new InvalidInputException('Event must be an instance of DomainEvent');
        }
    }
}
