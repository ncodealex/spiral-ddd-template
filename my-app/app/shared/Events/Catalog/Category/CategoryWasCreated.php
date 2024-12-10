<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Events\Catalog\Category;


class CategoryWasCreated extends CategoryEvent
{
    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $comment
     * @param string|null $placeholder
     */
    public function __construct(
        string         $id,
        public string  $name,
        public ?string $comment = null,
        public ?string $placeholder = null
    )
    {
        parent::__construct($id, self::CREATED);
    }

    public function payload(): array
    {
        return [
            'name' => $this->name,
            'comment' => $this->comment
        ];
    }
}
