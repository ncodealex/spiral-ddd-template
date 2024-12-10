<?php
declare(strict_types=1);

namespace Modules\Numerator\Application\Listener;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnUpdate;
use Cycle\ORM\ORM;
use Exception;
use Modules\Numerator\Domain\NumeratorServiceInterface;
use Showrent\Share\Domain\Entity\Numerator\NumerableEntityInterface;
use Showrent\Share\Domain\Exception\NumeratorExceptions;
use Showrent\Share\Domain\ValueObject\Number;
use Showrent\Share\Domain\ValueObject\Uuid;

final readonly class NumerableListener
{

    public function __construct(
        private NumeratorServiceInterface $service,
        private ORM                       $orm,
        private string                    $table,
        private string                    $field = 'number',
        private string                    $pk = 'uuid',

    )
    {
    }

    /**
     * @throws Exception
     */
    #[Listen(OnCreate::class)]
    #[Listen(OnUpdate::class)]
    public function __invoke(OnCreate|OnUpdate $event): void
    {
        if (!$event->entity instanceof NumerableEntityInterface) {
            NumeratorExceptions::entityNotImplementsInterface();
        }
        // It is OnUpdate
        /** @var Uuid|int|null|string $pk */
        $pk = $event->state->getValue($this->pk);
        if ($pk instanceof Uuid) {
            $pk = $pk->getValue();
        }
        $isCreate = $pk === null;

        /**
         * @var string $entityName
         */
        $entityName = $event->entity->getEntityName();
        /** @var string|null $number */
        $numberString = null;
        /** @var Number|null $number */
        $number = $event->state->getValue($this->field);

        if ($number instanceof Number) {
            $numberString = $number->getValue();
        }

        if ($isCreate && is_null($numberString)) {
            $dto = $this->service->generateAndSave($this->table, $entityName);
        } elseif ($isCreate && is_string($numberString)) {
            $dto = $this->service->insertOrGenerate($this->table, $entityName, $numberString);
        } else {
            /** @var string|int $pk */
            if (!$this->hasInOwnerNumberInDB($event->node->getRole(), $pk, $numberString)) {
                // Number is not exist in DB in Entity, then we can insert or generate it
                $dto = $this->service->insertOrGenerate($this->table, $entityName, $numberString);
            } else {
                // Number is exist in DB in Entity, then we can't update column number
                return;
            }
        }
        $number = $dto->getFullText();
        $event->state->register($this->field, Number::create($number));
    }

    protected function hasInOwnerNumberInDB(string $role, string|int $pk, string $number): bool
    {
        $pkColumn = !is_string($pk) ? 'id' : 'uuid';

        $repository = $this->orm->getRepository($role);
        $entity = $repository->findOne([
            $pkColumn => $pk,
            'number' => $number
        ]);
        return $entity !== null;
    }
}
