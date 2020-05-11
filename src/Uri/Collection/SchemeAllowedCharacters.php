<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;
/** ***********************************************************************************************
 * URI scheme allowed characters collection.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class SchemeAllowedCharacters implements CollectionInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return [
            '+',
            '-',
            '.',
        ];
    }
}