<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Application\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Router\Registry\RoutePatternRegistryInterface;

/**
 * Simple bootloaders that registers Domain repositories.
 */
class RouterBootloader extends Bootloader
{
    public function boot(
        RoutePatternRegistryInterface $patternRegistry,
    ): void
    {
        $patternRegistry->register(
            'uuid',
            '[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}'
        );
    }
}
