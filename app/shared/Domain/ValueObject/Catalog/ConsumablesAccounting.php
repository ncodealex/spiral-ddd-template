<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject\Catalog;


enum ConsumablesAccounting: string
{
    case NOT_USE = 'not_use';
    case FULL_REFUND = 'full_refund';
    case COST_WHEN_RETURNING = 'kit';
    case WRITE_OFF_COUNT = 'box';


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
        return self::NOT_USE->value;
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
