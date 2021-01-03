<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

use HNV\Http\UriTests\CombinationsProvider\CombinationsProviderInterface;

use function array_merge;
/** ***********************************************************************************************
 * URI full string with it`s parsed parts provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FullStringWithParsedParts implements CombinationsProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          value               =>
     *              http://login:password@site.com:10/page/sub-page?key=value&key2#fragment
     *              (full URI string),
     *          isValid             => true (URI string is correct and can be parsed correctly),
     *          scheme              => http,
     *          userInfo            => login:password,
     *          host                => site.com,
     *          port                => 10,
     *          authority           => login:password@site.com:10,
     *          path                => /page/sub-page,
     *          query               => key=value&key2,
     *          fragment            => fragment,
     *          valueNormalized     =>
     *              http://login:password@site.com:10/page/sub-page?key=value&key2#fragment
     *              (full URI string in normalized form),
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        foreach ([
            SchemeCombinations::get(),
            AuthorityCombinations::get(),
//            PathCombinations::get(),
//            QueryCombinations::get(),
//            FragmentCombinations::get(),
        ] as $dataSet) {
            $result = array_merge($result, $dataSet);
        }

        return $result;
    }
}