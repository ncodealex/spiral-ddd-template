<?php
declare(strict_types=1);

namespace Modules\Numerator\Infrastructure\CycleORM\Repository;

use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Exception;
use Modules\Numerator\Domain\NumeratorInterface;
use Modules\Numerator\Domain\NumeratorPartsDTO;
use Modules\Numerator\Domain\NumeratorPersistRepositoryInterface;

class NumeratorPersistRepository implements NumeratorPersistRepositoryInterface
{
    private DatabaseInterface $database;


    public function __construct(
        DatabaseManager $db
    )
    {
        $this->database = $db->database(NumeratorInterface::DEFAULT_DB);
    }

    /**
     * @throws Exception
     */
    public function insert(NumeratorPartsDTO $parts, string $tableName): int
    {
        $insertId = $this->database->insert($tableName)
            ->values([
                'entity_name' => $parts->entityName,
                'prefix' => $parts->prefix,
                'number' => $parts->number,
                'postfix' => $parts->postfix,
                'full_text' => $parts->getFullText()
            ])
            ->run();
        if (!is_int($insertId)) {
            throw new Exception('Error insert number');
        }

        return $insertId;
    }
}
