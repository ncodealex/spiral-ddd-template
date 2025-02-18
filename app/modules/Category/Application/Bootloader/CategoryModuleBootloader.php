<?php

namespace Modules\Category\Application\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\BootloadManager\StrategyBasedBootloadManager;
use Throwable;

class CategoryModuleBootloader extends Bootloader
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
