<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Unit;

use Shared\Domain\Bus\Event\DomainEvent;

abstract class UnitEvent extends DomainEvent
{
    public const string CREATED = 'created';
    public const string RESTORED_FROM_TRASH = 'restored_from_trash';
    public const string SEND_TO_TRASH = 'send_to_trash';
    public const string SET_DEFAULT = 'set_default';
    public const string UNSET_DEFAULT = 'unset_default';
    public const string UPDATE_NAME = 'updated_name';

    public const string UPDATE_SHORT_NAME = 'update_short_name';
    public const string UPDATE_FOR_PACKAGING = 'update_for_packaging';
    public const string UPDATE_PRECISION = 'update_precision';

    public const string UPDATE_SORT = 'update_sort';


    public function __construct(string $aggregateId, string $eventName = 'catalog')
    {
        parent::__construct('catalog', 'unit', $eventName, $aggregateId);
    }

    abstract public function payload(): array;
}
