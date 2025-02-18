<?php
declare(strict_types=1);

namespace Modules\Numerator\Infrastructure\CycleORM\Specification;

use Modules\Numerator\Domain\NumeratorPartsDTO;
use Modules\Numerator\Domain\NumeratorRepositoryInterface;
use Modules\Numerator\Domain\NumeratorUniqSpecificationInterface;

final class NumeratorUniqSpecification implements NumeratorUniqSpecificationInterface
{
    public function __construct(
        protected NumeratorRepositoryInterface $repository
    )
    {
    }


    public function isSatisfiedBy(string $entityName, string $fullText, string $table): bool
    {
        $result = $this->repository
            ->findOneByText($entityName, $fullText, $table);
        return $result instanceof NumeratorPartsDTO;

    }
}
