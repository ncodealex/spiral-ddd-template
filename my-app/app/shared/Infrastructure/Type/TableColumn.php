<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Infrastructure\Type;

final readonly class TableColumn
{
    public const string UUID = 'id';
    public const string CREATED_AT = 'created_at';
    public const string UPDATE_AT = 'update_at';
    public const string DELETE_AT = 'delete_at';

    public const string HELD = 'held';

    public const string HELD_AT = 'held_at';

    public const string DOCUMENT_DATE = 'document_date';

    public const string IS_ACTIVE = 'is_active';

    public const string VERSION = 'version';

    public const string IS_SYSTEM = 'is_system';

    public const string NAME = 'name';

    public const string NUMBER = 'number';

    public const string SORT = 'sort';
}
