<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Infrastructure\CycleORM\TypeCaster;

use Cycle\ORM\Parser\CastableInterface;
use Cycle\ORM\Parser\UncastableInterface;
use RuntimeException;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\ValueObjectInterface;

class ValueObjectTypecaster implements CastableInterface, UncastableInterface
{

    const string NAME = 'name';
    const string COLOR = 'color';

    const array ALLOWED_TYPES = [
        self::NAME,
        self::COLOR,
    ];

    /** @var non-empty-string[] */
    private array $rules = [];

    /**
     * @inheritDoc
     */
    public function cast(array $data): array
    {
        /**
         * @var non-empty-string $key
         * @var mixed            $rule
         */
        foreach ($this->rules as $column => $rule) {
            if (!isset($data[$column])) {
                continue;
            }
            $data[$column] = $this->getValueObjectByName($rule, $data[$column]);
        }

        return $data;
    }

    protected function getValueObjectByName(string $name, mixed $arg): mixed
    {
        $class = match ($name) {
            self::NAME => Name::class,
            self::COLOR => Color::class,
            default => throw new RuntimeException("Unknown value object name: $name"),
        };

        return call_user_func([$class, 'create'], $arg, strtolower(self::NAME));
    }

    /**
     * @inheritDoc
     */
    public function setRules(array $rules): array
    {
        /**
         * @var non-empty-string $key
         * @var mixed            $rule
         */
        foreach ($rules as $key => $rule) {
            if (in_array(strtolower($rule), self::ALLOWED_TYPES, true)) {
                unset($rules[$key]);
                $this->rules[$key] = $rule;
            }
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function uncast(array $data): array
    {
        foreach ($this->rules as $column => $rule) {
            if (!isset($data[$column]) || !$data[$column] instanceof ValueObjectInterface) {
                continue;
            }
            $data[$column] = $data[$column]->getValue();
        }

        return $data;
    }
}
