<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use Showrent\Share\Domain\ValueObject\Comment;

/** @deprecated */
trait HasCommentTrait
{
    /**
     * @psalm-suppress MissingConstructor
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string(450)', nullable: true, typecast: Comment::class,)]
    protected ?Comment $comment = null;

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment = null): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function setCommentFromString(?string $comment = null): self
    {
        $this->comment = Comment::create($comment);
        return $this;
    }
}
