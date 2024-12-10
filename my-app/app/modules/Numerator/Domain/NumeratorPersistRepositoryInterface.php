<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain;

interface NumeratorPersistRepositoryInterface
{
    public function insert(NumeratorPartsDTO $parts, string $tableName): int;
}
