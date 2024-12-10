<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Shared\Domain\ValueObject\Uuid;

trait HasUuidPKTrait
{
    /**
     * @psalm-suppress MissingConstructor
     */
    #[Column(type: 'uuid', primary: true, typecast: 'uuid')]
    private Uuid $uuid;

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function generateUuid(): self
    {
        $this->uuid = Uuid::generate();
        return $this;
    }
}
