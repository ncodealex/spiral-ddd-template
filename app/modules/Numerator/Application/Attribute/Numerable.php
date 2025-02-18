<?php
declare(strict_types=1);

namespace Modules\Numerator\Application\Attribute;

use Attribute;
use Cycle\ORM\Entity\Behavior\Schema\BaseModifier;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Modules\Numerator\Application\Listener\NumerableListener;


#[Attribute(Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class Numerable extends BaseModifier
{
    public function __construct(
        private readonly string $table,
        private readonly string $field = 'number',
        private string          $pk = 'uuid',
    )
    {
    }

    protected function getListenerClass(): string
    {
        return NumerableListener::class;
    }

    protected function getListenerArgs(): array
    {
        return [
            'table' => $this->table,
            'field' => $this->field,
            'pk' => $this->pk,
            'entity' => 'entity',
        ];
    }
}
