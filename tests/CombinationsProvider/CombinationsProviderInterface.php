<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;
/** ***********************************************************************************************
 * URI different combinations provider interface.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
interface CombinationsProviderInterface
{
    /** **********************************************************************
     * Get available combinations as set of data.
     *
     * @return  array[]                     Set of arrays,
     *                                      where each array describes in example tag.
     ************************************************************************/
    public static function get(): array;
}
