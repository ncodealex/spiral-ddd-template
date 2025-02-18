<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Bus;

use Shared\Domain\ValueObject\Uuid;

abstract class Message
{
    public function __construct(
        private Uuid $messageId
    )
    {
    }

    public function messageId(): Uuid
    {
        return $this->messageId;
    }

    abstract public function messageType(): string;
}
