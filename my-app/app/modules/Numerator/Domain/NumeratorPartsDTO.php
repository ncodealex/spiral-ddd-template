<?php
declare(strict_types=1);

namespace Modules\Numerator\Domain;

use Stringable;

class NumeratorPartsDTO implements Stringable
{

    public function __construct(
        public string  $entityName,
        public ?string $prefix = null,
        public ?int    $number = null,
        public ?string $postfix = null,
    )
    {
    }

    public function getFullText(): string
    {
        $str = '';
        if (!is_null($this->prefix)) {
            $str .= $this->prefix;
        }
        if (!is_null($this->number)) {
            $str .= $this->number;
        }
        if (!is_null($this->postfix)) {
            $str .= $this->postfix;
        }

        return $str;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getFullText();
    }
}
