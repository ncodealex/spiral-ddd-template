<?php

declare(strict_types=1);

namespace Shared\Infrastructure\CycleORM\Collection;

use Cycle\ORM\Collection\CollectionFactoryInterface;
use Cycle\ORM\Collection\Pivoted\PivotedCollection;
use Cycle\ORM\Exception\CollectionFactoryException;
use Doctrine\Common\Collections\Collection;
use Shared\Domain\Aggregate\AggregateRootCollection;
use Shared\Domain\Entity\EntityInterface;
use Traversable;
use function is_array;
use function iterator_to_array;

/**
 * Stores related items doctrine collection.
 * Items and Pivots for `Many to Many` relation stores in {@see PivotedCollection}.
 *
 * @template TCollection of AggregateRootCollection
 *
 * @template-implements CollectionFactoryInterface<TCollection>
 */
final class AggregateCollectionFactory implements CollectionFactoryInterface
{
    /** @var class-string<TCollection> */
    private string $class = AggregateRootCollection::class;

    public function __construct()
    {
        if (!class_exists(AggregateRootCollection::class, true)) {
            throw new CollectionFactoryException(
                sprintf(
                    'There is no %s class. To resolve this issue you can install `doctrine/collections` package.',
                    AggregateRootCollection::class
                )
            );
        }
    }

    public function getInterface(): ?string
    {
        return Collection::class;
    }

    public function withCollectionClass(string $class): static
    {
        $clone = clone $this;
        $clone->class = $class;
        return $clone;
    }

    /**
     * @param iterable $data
     *
     * @return AggregateRootCollection
     */
    public function collect(iterable $data): AggregateRootCollection
    {
        $data = match (true) {
            is_array($data) => $data,
            $data instanceof Traversable => iterator_to_array($data),
            default => throw new CollectionFactoryException('Unsupported iterable type.'),
        };

        if (empty($data)) {
            return new $this->class();
        }
        return new $this->class($this->mapToUuidKey($data));
    }

    protected function mapToUuidKey(array $data): array
    {
        /** @var array<string, EntityInterface|mixed> $result */
        $result = [];
        foreach ($data as $item) {
            if ($item instanceof EntityInterface) {
                $result[(string)$item->getId()] = $item;
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
}
