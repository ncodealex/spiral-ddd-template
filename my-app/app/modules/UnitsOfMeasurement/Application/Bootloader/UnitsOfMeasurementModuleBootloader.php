<?php

namespace Modules\UnitsOfMeasurement\Application\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\BootloadManager\StrategyBasedBootloadManager;
use Throwable;

class UnitsOfMeasurementModuleBootloader extends Bootloader
{
    /**
     * @throws Throwable
     */
    public function boot(
        StrategyBasedBootloadManager $strategyBasedBootloader,
    ): void
    {
        $strategyBasedBootloader->bootload([
            PersistenceBootloader::class,
        ]);
    }
}
