<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain\Exception\Numerator;

use Shared\Domain\Exception\InvalidInputException;

class NumberIsAlreadyExistException extends InvalidInputException
{

    public function __construct(string $number)
    {
        parent::__construct(sprintf('Number %s is already exist', $number));
    }

}
