<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Application\Bootloader;

use Psr\Container\ContainerInterface;
use Shared\Domain\ValueObject\Response\ApiResponseInterface;
use Showrent\Share\Application\Validation\Checker\QueryChecker;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\BootloadManager\StrategyBasedBootloadManager;
use Spiral\Bootloader\I18nBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Router\Registry\RoutePatternRegistryInterface;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Throwable;

class SharedBootloader extends Bootloader
{

    protected const DEPENDENCIES = [
        ValidatorBootloader::class,
        I18nBootloader::class,
    ];


    /**
     * @throws Throwable
     */
    public function boot(
        ValidatorBootloader           $validation,
        StrategyBasedBootloadManager  $strategyBasedBootloader,
        PrototypeBootloader           $prototype,
        RoutePatternRegistryInterface $patternRegistry,
        ContainerInterface            $container,
    ): void
    {

        $strategyBasedBootloader->bootload([
            PersistenceBootloader::class,
            RouterBootloader::class,
        ]);
    }

    public function init(I18nBootloader $i18n): void
    {
        $i18n->addDirectory(directory('app') . 'shared/locale');
    }

}
