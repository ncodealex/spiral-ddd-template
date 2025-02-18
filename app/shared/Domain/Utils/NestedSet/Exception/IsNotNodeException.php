<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Utils\NestedSet\Exception;

use Shared\Domain\Exception\InvalidInputException;
use Shared\Domain\Utils\NestedSet\NodeInterface;

class IsNotNodeException extends InvalidInputException
{
    public function __construct()
    {
        parent::__construct(sprintf('Invalid items type, waiting for %s', NodeInterface::class));
    }
}
