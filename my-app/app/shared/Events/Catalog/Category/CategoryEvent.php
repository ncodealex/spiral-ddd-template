<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;

use Shared\Domain\Bus\Event\DomainEvent;

abstract class CategoryEvent extends DomainEvent
{
    public const string CREATED = 'created';
    public const string RESTORED_FROM_TRASH = 'restored_from_trash';
    public const string SEND_TO_TRASH = 'send_to_trash';
    public const string SET_DEFAULT = 'set_default';
    public const string UNSET_DEFAULT = 'unset_default';
    public const string UPDATED_NAME = 'updated_name';
    public const string UPDATE_SORT = 'update_sort';
    public const string UPDATE_PLACEHOLDER = 'update_placeholder';
    public const string REMOVE_PLACEHOLDER = 'remove_placeholder';
    public const string CREATE_PLACEHOLDER = 'create_placeholder';
    public const string UPDATE_NODE_PARAMS = 'update_node_params';

    public const string SET_PARENT = 'set_parent';
    public const string REMOVE_PARENT = 'remove_parent';


    public function __construct(string $aggregateId, string $eventName = 'catalog')
    {
        parent::__construct('catalog', 'category', $eventName, $aggregateId);
    }

    abstract public function payload(): array;
}
