<?php

namespace Shared\Application\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shared\Domain\Factory\DefaultCurrencyInterface;
use Shared\Entity\CurrentUserCompanyInterface;
use Spiral\Core\Attribute\Proxy;

final readonly class CompanyCurrencyService implements DefaultCurrencyInterface
{

    public function __construct(
        #[Proxy]
        private ContainerInterface $container,
    )
    {
    }

    /**
     */
    public function getCurrency(): string
    {
        try {
            /** @var CurrentUserCompanyInterface $user */
            $user = $this->container->get(CurrentUserCompanyInterface::class);
            return $user->getCurrency();
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return 'USD_CompanyCurrencyService';
        }
    }
}
