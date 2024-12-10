<?php

namespace Shared\Domain\Embed;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Embeddable;
use Shared\Domain\ValueObject\UnitLength;
use Shared\Domain\ValueObject\UnitVolume;

#[Embeddable(columnPrefix: 'dimension_')]
/**
 * @psalm-suppress RedundantPropertyInitializationCheck
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class DimensionsEmbed
{
    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', nullable: false, default: 0, typecast: UnitLength::class)]
    private UnitLength $width;
    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', nullable: false, default: 0, typecast: UnitLength::class)]
    private UnitLength $height;
    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', nullable: false, default: 0, typecast: UnitLength::class)]
    private UnitLength $length;
    /**
     *  Volume in sq meter
     *
     * @psalm-suppress RedundantPropertyInitializationCheck
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', nullable: false, default: 0, typecast: UnitVolume::class)]
    private UnitVolume $volume;

    public function __construct(
        private readonly string $columnPrefix = 'dimension_',
    )
    {
    }

    public function getVolume(): UnitVolume
    {
        return $this->volume;
    }

    public function calculateVolume(): self
    {
        $this->volume = UnitVolume::createFromSize($this->getLength(), $this->getWidth(), $this->getHeight());
        return $this;
    }

    public function getLength(): UnitLength
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = UnitLength::create($length, $this->columnPrefix . 'length');
        return $this;
    }

    public function getWidth(): UnitLength
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = UnitLength::create($width, $this->columnPrefix . 'width');
        return $this;
    }

    public function getHeight(): UnitLength
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = UnitLength::create($height, $this->columnPrefix . 'height');
        return $this;
    }
}
