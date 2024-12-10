<?php
declare(strict_types=1);

namespace Modules\Category\Infrastructure\CycleORM\Table;

interface ItemTable
{
    public const string TABLE = 'categories';
    public const string ID = 'id';

    public const string NAME = 'name';
    public const string COMMENT = 'comment';
    public const string OWNER = 'owner';
    public const string PLACEHOLDER = 'placeholder';
    public const string IS_SYSTEM = 'is_system';
    public const string PARENT_ID = 'parent_id';
    public const string SORT = 'sort';
    public const string IS_DEFAULT = 'is_default';
    public const string LEFT = 'left';
    public const string RIGHT = 'right';
    public const string DEPTH = 'depth';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';
    public const string VERSION = 'version';

    public const array COLUMNS = [
        self::ID,
        self::OWNER,
        self::PLACEHOLDER,
        self::NAME,
        self::COMMENT,
        self::IS_SYSTEM,
        self::PARENT_ID,
        self::SORT,
        self::IS_DEFAULT,
        self::LEFT,
        self::RIGHT,
        self::DEPTH,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
        self::VERSION,
    ];
}
