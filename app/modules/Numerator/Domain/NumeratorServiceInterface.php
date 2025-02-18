<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain;

interface NumeratorServiceInterface
{

    /**
     * @param string $table
     * @param string $entityName
     * @param string|null $number
     * @return NumeratorPartsDTO
     */
    public function insertOrGenerate(string $table, string $entityName, ?string $number = null): NumeratorPartsDTO;

    /**
     * @param string $table
     * @param string $entityName
     * @return NumeratorPartsDTO
     */
    public function generateAndSave(string $table, string $entityName): NumeratorPartsDTO;
}
