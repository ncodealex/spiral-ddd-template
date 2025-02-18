<?php
declare(strict_types=1);

namespace Modules\Numerator\Application\Handlers;

use Modules\Numerator\Domain\NumeratorServiceInterface;
use Showrent\Share\Application\Commands\Numerator\GenerateNumberCommand;
use Spiral\Cqrs\Attribute\CommandHandler;

final readonly class GenerateNumberHandler
{

    public function __construct(
        protected NumeratorServiceInterface $service
    )
    {

    }

    #[CommandHandler]
    public function __invoke(GenerateNumberCommand $command): string
    {
        $numberDTO = $this->service
            ->insertOrGenerate(
                $command->table,
                $command->entityName,
                $command->number
            );
        return $numberDTO->getFullText();
    }
}
