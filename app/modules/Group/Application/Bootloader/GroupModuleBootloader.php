<?php

namespace Modules\Group\Application\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\BootloadManager\StrategyBasedBootloadManager;
use Throwable;

class GroupModuleBootloader extends Bootloader
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
