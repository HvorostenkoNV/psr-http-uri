<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;
/** ***********************************************************************************************
 * URI query allowed characters collection.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class QueryAllowedCharacters implements CollectionInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return [
            '*',
            '-',
            '.',
            '_',
        ];
    }
}