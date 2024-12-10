<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Exception;

use Assert\LazyAssertionException;


/**
 * Class AssertException use for AppAssertLazy exception
 * problem with the validation of the input data
 * @package Showrent\Share\Domain\Exception
 */
class AssertsException extends LazyAssertionException
{
    protected $code = ExceptionCodeInterface::UNPROCESSABLE_ENTITY;

//    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
//    {
//        if ($previous instanceof LazyAssertionException) {
//            var_dump('lazy error');
//        } elseif ($previous instanceof InvalidArgumentException) {
//            var_dump('Assert\InvalidArgumentExceptio');
//        }
//
//        parent::__construct($message, $code, $previous);
//    }

//    public function getErrorMessages(): array
//    {
//
//        $errors = array_map(
//            fn(InvalidArgumentException $e) => $e->getMessage(),
//            $this->getErrorExceptions()
//        );
//
//        return $this->getPropertyPath() ? [$this->getPropertyPath() => $errors] : $errors;
//    }
}
