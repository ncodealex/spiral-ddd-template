<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Shared\Domain\Bus\Message;
use Shared\Domain\ValueObject\Uuid;

abstract class DomainEvent extends Message
{

    private const string EVENT_TYPE = 'domain_event';
    private string $eventId;
    private string $occurredOn;

    private int $aggregateVersion = - 1;

    public function __construct(
        private readonly string $domain,
        private readonly string $aggregateName,
        private readonly string $eventName,
        private readonly string $aggregateId,
        ?string                 $eventId = null,
        ?string                 $occurredOn = null

    )
    {
        $this->eventId = $eventId ?? Uuid::generate()->getValue();
        parent::__construct(Uuid::create($this->eventId));
        $this->occurredOn = $occurredOn ?? (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM);
    }

    public function jsonSerialize(): array
    {
        return [
            'eventId' => $this->eventId,
            'domain' => $this->domain,
            'eventName' => $this->eventName,
            'aggregateName' => $this->aggregateName,
            'aggregateId' => $this->aggregateId,
            'aggregateVersion' => $this->aggregateVersion,
            'payload' => $this->payload(),
            'occurredOn' => $this->occurredOn,
            'messageType' => $this->messageType(),
            'messageId' => $this->messageId()->getValue(),
        ];
    }

    abstract public function payload(): array;

    public function messageType(): string
    {
        return self::EVENT_TYPE;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function aggregateVersion(): int
    {
        return $this->aggregateVersion;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function setAggregateVersion(int $aggregateVersion): void
    {
        $this->aggregateVersion = $aggregateVersion;
    }
}
