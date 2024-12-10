<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject\Catalog;

enum KitContentControl: string
{
    case NOT_USE = 'not_use';
    case ALLOWED = 'allowed';

    case CONTROL = 'control';

    case WITH_ANALOGS = 'analogs_only';


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

    /**
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        return self::from((string)$value);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
