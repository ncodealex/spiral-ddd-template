<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Application\Attribute;

use Attribute;
use Cycle\ORM\Entity\Behavior\Schema\BaseModifier;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Shared\Application\Listener\UuidAttributeListener;
use Shared\Infrastructure\Type\TableColumn;


#[Attribute(Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class Uuid extends BaseModifier
{
    public function __construct(
        private readonly string $field = TableColumn::UUID,
    )
    {
    }

    protected function getListenerClass(): string
    {
        return UuidAttributeListener::class;
    }

    protected function getListenerArgs(): array
    {
        return [
            'field' => $this->field,
        ];
    }
}
