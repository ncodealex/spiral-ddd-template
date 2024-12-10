<?php
declare(strict_types=1);

namespace Modules\Numerator\Infrastructure\CycleORM\Repository;

use Cycle\Database\Database;
use Modules\Numerator\Domain\NumeratorPartsDTO;
use Modules\Numerator\Domain\NumeratorRepositoryInterface;

class NumeratorRepository implements NumeratorRepositoryInterface
{
    public function __construct(
        protected Database $database
    )
    {
    }

    public function findOneByText(string $entityName, string $fullText, string $table): NumeratorPartsDTO|null
    {
        /** @var array<string,string|null|numeric> $result */
        $result = $this->database->table($table)
            // @psalm-suppress  TooManyArguments
            ->select('prefix', 'number', 'postfix')
            ->where([
                'entity_name' => $entityName,
                'full_text' => $fullText
            ])->run()->fetch();

        if (!$result) {
            return null;
        }
        $prefix = is_string($result['prefix']) ? $result['prefix'] : null;
        $number = (int)$result['number'] + 1;
        $postfix = is_string($result['postfix']) ? $result['postfix'] : null;

        return new NumeratorPartsDTO(
            $entityName,
            $prefix,
            $number,
            $postfix
        );

    }

    public function getNextNumber(string $entityName, string $table): NumeratorPartsDTO
    {
        /** @var array<string,string|null|numeric> $result */
        $result = $this->database->table($table)
            // @psalm-suppress TooManyArguments
            ->select('prefix', 'number', 'postfix')
            ->where('entity_name', $entityName)
            ->andWhereNot('number', '=', null)
            ->limit(1)
            ->orderBy('id', 'DESC')
            ->run()->fetch();

        /** @var string|null $prefix */
        $prefix = null;
        /** @var int|null $number */
        $number = 1;
        /** @var string|null $postfix */
        $postfix = null;

        if ($result) {
            $prefix = is_string($result['prefix']) ? $result['prefix'] : null;
            $number = (int)$result['number'] + 1;
            $postfix = is_string($result['postfix']) ? $result['postfix'] : null;
        }
        return new NumeratorPartsDTO(
            $entityName,
            $prefix,
            $number,
            $postfix
        );
    }
}
