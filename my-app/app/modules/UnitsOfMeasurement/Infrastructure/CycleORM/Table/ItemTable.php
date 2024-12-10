<?php
declare(strict_types=1);

namespace Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Table;

interface ItemTable
{
    public const string TABLE = 'units_of_measurement';
    public const string ID = 'id';
    public const string NAME = 'name';
    public const string PRECISION = 'precision';
    public const string FOR_PACKAGING = 'for_packaging';
    public const string IS_SYSTEM = 'is_system';
    public const string IS_DEFAULT = 'is_default';
    public const string CREATED_AT = 'created_at';
    public const string UPDATED_AT = 'updated_at';
    public const string DELETED_AT = 'deleted_at';
    public const string VERSION = 'version';
    public const string SORT = 'sort';
}
