<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Bus\Event\DomainEvent;
use Shared\Domain\Entity\EntityInterface;
use Shared\Domain\ValueObject\Uuid;

abstract class AggregateRoot implements EntityInterface
{
    private array $domainEvents = [];

    abstract public function getId(): Uuid;

    /**
     * @return DomainEvent[]
     */
    final public function releaseEvents(): array
    {
        $currentVersion = $this instanceof VersioningAggregateInterface ? $this->getVersion() : - 1;
        $domainEvents = array_map(
            function (DomainEvent $domainEvent) use ($currentVersion) {
                $domainEvent->setAggregateVersion($currentVersion);
                return $domainEvent;
            },
            $this->domainEvents
        );
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
