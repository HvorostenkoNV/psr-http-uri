<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CollectionInterface;

use function array_merge;
/** ***********************************************************************************************
 * URI path allowed character`s collection.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class PathAllowedCharacters implements CollectionInterface
{
    public const NON_FIRST_CHARS = [
        ':',
    ];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return array_merge(self::NON_FIRST_CHARS, [
            '.',
            '-',
            '_',
            '~',
            '!',
            '$',
            '&',
            '\'',
            '(',
            ')',
            '*',
            '+',
            ',',
            ';',
            '=',
            '@',
        ]);
    }
}
