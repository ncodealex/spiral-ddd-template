<?php

namespace Modules\Numerator\Application\Bootloader;

use Modules\Numerator\Application\NumeratorService;
use Modules\Numerator\Domain\NumeratorPersistRepositoryInterface;
use Modules\Numerator\Domain\NumeratorRepositoryInterface;
use Modules\Numerator\Domain\NumeratorServiceInterface;
use Modules\Numerator\Domain\NumeratorUniqSpecificationInterface;
use Modules\Numerator\Infrastructure\CycleORM\Repository\NumeratorPersistRepository;
use Modules\Numerator\Infrastructure\CycleORM\Repository\NumeratorRepository;
use Modules\Numerator\Infrastructure\CycleORM\Specification\NumeratorUniqSpecification;
use Spiral\Boot\Bootloader\Bootloader;

class NumeratorModuleBootloader extends Bootloader
{
    const SINGLETONS = [
        NumeratorRepositoryInterface::class => NumeratorRepository::class,
        NumeratorPersistRepositoryInterface::class => NumeratorPersistRepository::class,
        NumeratorUniqSpecificationInterface::class => NumeratorUniqSpecification::class,
        NumeratorServiceInterface::class => NumeratorService::class,
    ];
}
