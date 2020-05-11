<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;
/** ***********************************************************************************************
 * URI domain name allowed characters collection.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class DomainAllowedCharacters implements CollectionInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return [
            '-',
        ];
    }
}