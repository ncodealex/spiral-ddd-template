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
use Shared\Domain\ValueObject\Uuid as UuidValueObject;
use function assert;
use function is_string;

class UuidTypecaster implements CastableInterface, UncastableInterface
{
    /** @var non-empty-string[] */
    private array $rules = [];

    /**
     * @inheritDoc
     */
    public function cast(array $data): array
    {
        /**
         * @var non-empty-string $key
         * @var mixed $rule
         */
        foreach ($this->rules as $column => $rule) {
            if (!isset($data[$column])) {
                continue;
            }
            assert(is_string($data[$column]));
            $data[$column] = UuidValueObject::create($data[$column]);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function setRules(array $rules): array
    {
        /**
         * @var non-empty-string $key
         * @var mixed $rule
         */
        foreach ($rules as $key => $rule) {
            if ($rule === 'uuid') {
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
            if (!isset($data[$column]) || !$data[$column] instanceof UuidValueObject) {
                continue;
            }

            $data[$column] = $data[$column]->getValue();
        }

        return $data;
    }
}
