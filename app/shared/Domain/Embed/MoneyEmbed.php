<?php

namespace Shared\Domain\Embed;

use Closure;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Embeddable;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command;
use Money\Money;
use RuntimeException;
use Showrent\Share\Domain\Factory\DefaultCurrencyInterface;
use Showrent\Share\Domain\Factory\MoneyFactoryInterface;

#[Embeddable(columnPrefix: 'money_')]
#[Behavior\Hook(
    callable: [MoneyEmbed::class, 'onCreate'],
    events: Command\OnCreate::class
)]
/**
 * @psalm-suppress RedundantPropertyInitializationCheck
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MoneyEmbed
{
    protected ?Closure $onGetDefaultCurrency;
    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', nullable: false, default: 0)]
    private int $amount;
    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string(6)', nullable: false, default: 'USD55')]
    private ?string $currencyCode;
    private ?Money $money = null;

    public function __construct(
        protected MoneyFactoryInterface    $factory,
        protected DefaultCurrencyInterface $defaultCurrency
    )
    {
        $this->onGetDefaultCurrency = null;
        $this->currencyCode = null;
        $this->amount = 0;
    }

    public static function onCreate(MoneyEmbed $embed): void
    {
        if ($embed->currencyCode === null) {
            $embed->currencyCode = $embed->getDefaultCurrencyCode();
        }
    }

    protected function getDefaultCurrencyCode(): string
    {
        if ($this->onGetDefaultCurrency === null) {
            return $this->defaultCurrency->getCurrency();
        }
        $currency = ($this->onGetDefaultCurrency)();
        if (is_string($currency)) {
            return $currency;
        } else {
            return $this->defaultCurrency->getCurrency()
                ?? throw new RuntimeException('Invalid onGetDefaultCurrency');
        }
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {

        if ($this->money === null) {
            $this->money = $this->factory->create($this->amount, $this->currencyCode);
        }
        return $this->money;
    }

    public function setMoney(Money $money): self
    {
        $this->money = $money;
        $this->amount = $money->getAmount();
        $this->currencyCode = $money->getCurrency()->getCode();
        return $this;
    }

    public function setOnGetDefaultCurrency(Closure $onGetDefaultCurrency): void
    {
        $this->onGetDefaultCurrency = $onGetDefaultCurrency;
    }


}
