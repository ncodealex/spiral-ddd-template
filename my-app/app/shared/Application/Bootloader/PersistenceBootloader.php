<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Application\Bootloader;


use Shared\Application\Factory\MoneyFactory;
use Shared\Application\Services\CompanyCurrencyService;
use Shared\Domain\Bus\Event\DomainEventDispatcherInterface;
use Shared\Domain\Factory\DefaultCurrencyInterface;
use Shared\Domain\Factory\MoneyFactoryInterface;
use Shared\Infrastructure\Bus\Event\InMemory\InMemoryDomainEventDispatcher;
use Spiral\Boot\Bootloader\Bootloader;

/**
 * Simple bootloaders that registers Domain repositories.
 */
class PersistenceBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            DefaultCurrencyInterface::class => CompanyCurrencyService::class,
            MoneyFactoryInterface::class => MoneyFactory::class,
            DomainEventDispatcherInterface::class => InMemoryDomainEventDispatcher::class
        ];
    }
}
