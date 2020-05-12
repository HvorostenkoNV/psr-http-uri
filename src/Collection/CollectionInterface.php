<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;
/** ***********************************************************************************************
 * Collection interface.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
interface CollectionInterface
{
    /** **********************************************************************
     * Get collection data.
     *
     * @return  array                       Collection data.
     ************************************************************************/
    public static function get(): array;
}