<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Application\Listener;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnUpdate;
use Shared\Domain\ValueObject\Uuid;
use Shared\Infrastructure\Type\TableColumn;

final readonly class UuidAttributeListener
{
    public function __construct(
        private string $field = TableColumn::UUID
    )
    {
    }

    #[Listen(OnCreate::class)]
    #[Listen(OnUpdate::class)]
    public function __invoke(OnCreate|OnUpdate $event): void
    {
        if (!isset($event->state->getData()[$this->field]) ||
            $event->state->getData()[$this->field] === null
        ) {
            $event->state->register($this->field, Uuid::generate());
        }
    }

}
