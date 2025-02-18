<?php

namespace Shared\Domain\ValueObject\Group;

enum GroupOwner: string
{
    case DEFAULT = 'default';

    public static function typecast(mixed $value): self
    {
        return self::from((string)$value);
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return string
     */
    public static function defaultValue(): string
    {
        return self::DEFAULT->value;
    }

    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return self::from((string)$value);
    }

    public function jsonSerialize(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
