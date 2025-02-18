<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Brand;

use Shared\Domain\Bus\Event\DomainEvent;

abstract class BrandEvent extends DomainEvent
{
    public const string CREATED = 'created';
    public const string RESTORED_FROM_TRASH = 'restored_from_trash';
    public const string SEND_TO_TRASH = 'send_to_trash';
    public const string SET_DEFAULT = 'set_default';
    public const string UNSET_DEFAULT = 'unset_default';
    public const string UPDATED_NAME = 'updated_name';
    public const string UPDATE_SORT = 'update_sort';


    public function __construct(string $aggregateId, string $eventName = 'catalog')
    {
        parent::__construct('catalog', 'brand', $eventName, $aggregateId);
    }

    abstract public function payload(): array;
}
