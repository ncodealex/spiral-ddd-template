<?php
declare(strict_types=1);

namespace Modules\Numerator\Application;

use Exception;
use Modules\Numerator\Domain\Exception\NumeratorExceptions;
use Modules\Numerator\Domain\NumeratorPartsDTO;
use Modules\Numerator\Domain\NumeratorPersistRepositoryInterface;
use Modules\Numerator\Domain\NumeratorRepositoryInterface;
use Modules\Numerator\Domain\NumeratorServiceInterface;
use Modules\Numerator\Domain\NumeratorUniqSpecificationInterface;


final readonly class NumeratorService implements NumeratorServiceInterface
{

    public function __construct(
        private NumeratorRepositoryInterface        $repository,
        private NumeratorPersistRepositoryInterface $persistRepository,
        private NumeratorUniqSpecificationInterface $specification
    )
    {
    }

    /**
     * @param string      $table
     * @param string      $entityName
     * @param string|null $number
     *
     * @inheritDoc
     * @throws Exception
     */
    public function insertOrGenerate(string $table, string $entityName, ?string $number = null): NumeratorPartsDTO
    {
        if (strlen((string)$number) === 0 ||
            is_null($number)
        ) {
            /// Generate new number
            $dto = $this->generateNext($entityName, $table);
        } else {
            /// Check user number
            $dto = $this->stringToParts($number, $entityName);
            // Check unique
            $fullText = $dto->getFullText();
            if ($this->specification->isSatisfiedBy($entityName, $fullText, $table)) {
                NumeratorExceptions::numberIsAlreadyExist($number);
            }
        }

        if (is_null($dto->prefix) && is_null($dto->number)) {
            NumeratorExceptions::prefixAndNumberIsNull();
        }

        $this->insert($dto, $table);
        return $dto;
    }

    /**
     * @param string $entityName
     * @param string $table
     *
     * @return NumeratorPartsDTO
     */
    protected function generateNext(string $entityName, string $table): NumeratorPartsDTO
    {
        return $this->repository->getNextNumber($entityName, $table);
    }

    /**
     * @param string $string
     * @param string $entityName
     *
     * @return NumeratorPartsDTO
     */
    protected function stringToParts(string $string, string $entityName): NumeratorPartsDTO
    {
        preg_match('/\d+$/', $string, $matches);
        $matches = $matches[0] ?? null;

        $number = is_null($matches) ? null : (int)$matches;
        $splitResult = preg_split('/\d+$/', $string)[0] ?? '';
        $prefix = strlen($splitResult) > 0 ? (string)$splitResult : null;

        return new NumeratorPartsDTO(
            $entityName,
            $prefix,
            $number,
            null,
        );
    }

    /**
     * @param NumeratorPartsDTO $parts
     * @param string            $table
     *
     * @return int
     */
    protected function insert(NumeratorPartsDTO $parts, string $table): int
    {
        return $this->persistRepository->insert($parts, $table);
    }

    /**
     * @param string     $table
     * @param string     $entityName
     * @param string|int $pk
     *
     * @inheritDoc
     */
    public function generateAndSave(string $table, string $entityName): NumeratorPartsDTO
    {
        $dto = $this->generateNext($entityName, $table);
        $this->insert($dto, $table);
        return $dto;
    }
}
