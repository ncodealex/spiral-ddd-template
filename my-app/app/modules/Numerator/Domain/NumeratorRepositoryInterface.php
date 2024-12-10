<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain;

interface NumeratorRepositoryInterface
{
    public function findOneByText(string $entityName, string $fullText, string $table): NumeratorPartsDTO|null;

    public function getNextNumber(string $entityName, string $table): NumeratorPartsDTO;
}
