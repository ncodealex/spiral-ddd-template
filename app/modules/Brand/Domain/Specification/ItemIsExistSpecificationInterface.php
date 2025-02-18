<?php
declare(strict_types=1);

namespace Modules\Brand\Domain\Specification;

use Shared\Domain\ValueObject\Uuid;

interface ItemIsExistSpecificationInterface
{
    public function isSatisfiedBy(Uuid $uuid, bool $inTrash = false): bool;
}
