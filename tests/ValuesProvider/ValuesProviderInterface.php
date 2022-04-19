<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;
/** ***********************************************************************************************
 * Values provider interface.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
interface ValuesProviderInterface
{
    /** **********************************************************************
     * Get valid values with their normalized representation.
     *
     * @return  array                       Data set, where key is value and
     *                                      value is its normalized representation.
     ************************************************************************/
    public static function getValidValues(): array;
    /** **********************************************************************
     * Get invalid values set.
     *
     * @return  array                       Invalid values data set.
     ************************************************************************/
    public static function getInvalidValues(): array;
}
