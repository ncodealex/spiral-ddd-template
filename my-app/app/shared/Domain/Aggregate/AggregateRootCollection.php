<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Doctrine\Common\Collections\ArrayCollection;
use Shared\Domain\Bus\Event\DomainEvent;

/**
 * @template T
 * @extends ArrayCollection<string,T>
 */
final class AggregateRootCollection extends ArrayCollection
{

    /**
     * @return DomainEvent[]
     */
    final public function releaseEvents(): array
    {
        /** @var DomainEvent[] $domainEvents */
        $domainEvents = [];
        foreach ($this as $aggregateRoot) {
            /** @var AggregateRoot $aggregateRoot */
            $domainEvents = array_merge($domainEvents, $aggregateRoot->releaseEvents());
        }
        return $domainEvents;
    }
}
