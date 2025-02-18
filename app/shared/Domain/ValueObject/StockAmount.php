<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\ValueObject;

use Shared\Domain\Validation\AppAssertLazy;

final readonly class StockAmount implements ValueObjectInterface
{

    private function __construct(
        private int $value,
    )
    {
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     *
     * @inheritDoc
     */
    public static function create(mixed $value, ?string $propertyPath = null): self
    {
        AppAssertLazy::lazy()
            ->that($value, $propertyPath)
            ->notEmpty()
            ->integer()
            ->verifyNow();

        return new self((int)$value);
    }

    public static function sum(StockAmount ...$stockAmounts): self
    {
        $sum = 0;
        foreach ($stockAmounts as $stockAmount) {
            $sum += $stockAmount->getValue();
        }
        return new self($sum);
    }

    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     *  Subtract two StockAmount objects
     *
     * @param StockAmount $stockAmount1
     * @param StockAmount $stockAmount2
     *
     * @return self
     */
    public static function subtract(StockAmount $stockAmount1, StockAmount $stockAmount2): self
    {
        return new self($stockAmount1->getValue() - $stockAmount2->getValue());
    }

    /**
     *  Multiply StockAmount object by multiplier
     *
     * @param StockAmount $stockAmount
     * @param int         $multiplier
     *
     * @return self
     */
    public static function multiply(StockAmount $stockAmount, int $multiplier): self
    {
        return new self($stockAmount->getValue() * $multiplier);
    }
}
