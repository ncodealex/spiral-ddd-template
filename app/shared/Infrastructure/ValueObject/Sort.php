<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Infrastructure\ValueObject;

use JsonSerializable;
use Shared\Domain\Validation\AppAssertLazy;

final class Sort implements JsonSerializable
{
    const ASC = 'ASC';

    const DESC = 'DESC';

    private string $column;
    private string $direction;

    public function __construct(string $column, string $direction)
    {

        AppAssertLazy::lazy()
            ->that($column, 'sort')
            ->notEmpty()
            ->string()
            ->minLength(3)
            ->maxLength(255)
            ->verifyNow();

        $message = l('Invalid property sort direction. Valid values are "asc" or "desc".', [], 'errors');

        AppAssertLazy::lazy()
            ->that($direction, 'direction')
            ->notEmpty()
            ->inArray([self::ASC, self::DESC], $message)
            ->verifyNow();

        $this->column = $sort;
        $this->direction = $direction;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function jsonSerialize(): array
    {
        return [
            'column' => $this->column,
            'direction' => $this->direction,
        ];
    }
}
