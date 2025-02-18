<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain\Exception;


use Modules\Numerator\Domain\Exception\Numerator\NumberEntityNotImplementsInterfaceException;
use Modules\Numerator\Domain\Exception\Numerator\NumberIsAlreadyExistException;
use Modules\Numerator\Domain\Exception\Numerator\NumberPrefixAndNumberIsNullException;

final class NumeratorExceptions
{
    const MESSAGE_NUMBER_IS_ALREADY_EXIST = 'Number {number} is already exist';

    public static function numberIsAlreadyExist(string $number): void
    {
        throw new NumberIsAlreadyExistException($number);
    }

    public static function prefixAndNumberIsNull(): void
    {
        throw new NumberPrefixAndNumberIsNullException();
    }

    public static function entityNotImplementsInterface(): void
    {
        throw new NumberEntityNotImplementsInterfaceException();
    }
}
