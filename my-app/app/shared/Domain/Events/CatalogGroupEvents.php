<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\Domain\Events;

interface CatalogGroupEvents
{
    public const string CREATED = 'group_was_created';
    public const string SET_DEFAULT = 'group_was_set_default';
}
