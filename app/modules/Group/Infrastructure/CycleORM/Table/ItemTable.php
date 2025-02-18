<?php
declare(strict_types=1);

namespace Modules\Group\Infrastructure\CycleORM\Table;

interface ItemTable
{
    public const string TABLE = 'groups';

    public const string ID = 'id';
    public const string OWNER = 'owner';
    public const string NAME = 'name';
    public const string COLOR = 'color';
    public const string IS_SYSTEM = 'is_system';
    public const string COMMENT = 'comment';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';
    public const string VERSION = 'version';
    public const string SORT = 'sort';
    public const string PLACEHOLDER = 'placeholder';
    public const string IS_DEFAULT = 'is_default';

    public const array COLUMNS = [
        self::ID,
        self::NAME,
        self::COLOR,
        self::IS_SYSTEM,
        self::COMMENT,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
        self::VERSION,
        self::SORT,
        self::PLACEHOLDER,
        self::IS_DEFAULT,
    ];
}
