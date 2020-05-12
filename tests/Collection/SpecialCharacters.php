<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\Collection;

use HNV\Http\Uri\Collection\CollectionInterface;
/** ***********************************************************************************************
 * Chars collection.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class SpecialCharacters implements CollectionInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return [
            '`', '\'', '"',

            '[', ']',
            '{', '}',
            '(', ')',

            '\\', '|', '/',

            '+', '-', '=',
            '*', '%',

            '^', '<', '>',

            ',', '.', ':', ';',

            '~', '!', '@',
            '#', '№', '$',
            '&', '?', '_',
        ];
    }
}