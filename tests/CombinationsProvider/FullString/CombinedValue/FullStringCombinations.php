<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\UriTests\CombinationsProvider\CombinationsProviderInterface;

use function array_merge;
/** ***********************************************************************************************
 * URI full string different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FullStringCombinations implements CombinationsProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          scheme      => http,
     *          login       => login,
     *          password    => password,
     *          host        => site.com,
     *          port        => 10,
     *          path        => /page/sub-page,
     *          query       => key=value&key2,
     *          fragment    => fragment,
     *          value       => http://login:password@site.com:10/page/sub-page?key=value&key2#fragment
     *                         (full URI string),
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        foreach ([
            SchemeCombinations::get(),
            AuthorityCombinations::get(),
            PathCombinations::get(),
            QueryCombinations::get(),
            FragmentCombinations::get(),
        ] as $dataSet) {
            $result = array_merge($result, $dataSet);
        }

        return $result;
    }
}