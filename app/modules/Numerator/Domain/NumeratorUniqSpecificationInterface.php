<?php

namespace Modules\Numerator\Domain;

interface NumeratorUniqSpecificationInterface
{
    public function isSatisfiedBy(string $entityName, string $fullText, string $table): bool;
}
