<?php
declare(strict_types=1);

namespace Modules\Brand\Infrastructure\CycleORM\Table;

interface ItemTable
{
    public const string TABLE = 'brands';

    public const string ID = 'id';
    public const string OWNER = 'owner';
    public const string NAME = 'name';
    public const string COMMENT = 'comment';
    public const string IS_SYSTEM = 'is_system';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';
    public const string VERSION = 'version';
    public const string SORT = 'sort';
    public const string AVATAR_ID = 'avatar_id';
    public const string IS_DEFAULT = 'is_default';
    public const string SKU_PROPERTY = 'sku_property';
    public const string COUNTRY = 'country';

    public const array COLUMNS = [
        self::ID,
        self::OWNER,
        self::NAME,
        self::COMMENT,
        self::IS_SYSTEM,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
        self::VERSION,
        self::SORT,
        self::AVATAR_ID,
        self::IS_DEFAULT,
        self::SKU_PROPERTY,
        self::COUNTRY,
    ];
}
