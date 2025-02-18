<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

use Cycle\Schema\Renderer\MermaidRenderer\Stringable;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Validation\AppAssertLazy;

final readonly class Uuid implements ValueObjectInterface, Stringable
{
    public function __construct(
        private UuidInterface $uuid,
    )
    {
    }

    public static function generate(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid7());
    }

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->notEmpty()
            ->uuid()
            ->verifyNow();
        return new self(\Ramsey\Uuid\Uuid::fromString((string)$value));
    }

    public static function isValid(mixed $value): bool
    {
        return \Ramsey\Uuid\Uuid::isValid((string)$value);
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    public function getValue(): string
    {
        return $this->uuid->toString();
    }
}
