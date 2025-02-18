<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

enum PackagingType: string implements ValueObjectInterface
{
    case NOT_USED = 'not_used';
    case PIECE = 'piece';
    case WEIGHT = 'weight';
    case WATER = 'water';

    /**
     * @return array
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return string
     */
    public static function default(): string
    {
        return self::NOT_USED->value;
    }

    public static function create(mixed $value, ?string $propertyPath = null): PackagingType
    {
        return self::from((string)$value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
