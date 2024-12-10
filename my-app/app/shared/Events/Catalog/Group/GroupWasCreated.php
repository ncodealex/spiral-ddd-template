<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Group;


class GroupWasCreated extends GroupEvent
{
    protected int $sort = 0;

    /**
     * @param string      $id
     * @param string      $name
     * @param string      $color
     * @param bool        $isDefault
     * @param bool        $isSystem
     * @param string|null $comment
     * @param string|null $placeholder
     */
    public function __construct(
        string         $id,
        public string  $name,
        public string  $color,
        protected bool $isDefault = false,
        protected bool $isSystem = false,
        public ?string $comment = null,
        public ?string $placeholder = null,
    )
    {
        parent::__construct($id, self::CREATED);
    }

    public function payload(): array
    {
        return [
            'name' => $this->name,
            'color' => $this->color,
            'placeholder' => $this->placeholder,
            'comment' => $this->comment,
            'isDefault' => $this->isDefault,
            'sort' => $this->sort,
            'isSystem' => $this->isSystem
        ];
    }
}
