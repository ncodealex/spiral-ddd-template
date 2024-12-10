<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Entity;

use Shared\Domain\ValueObject\Uuid;

interface EntityInterface
{
    public function getId(): Uuid;
}
