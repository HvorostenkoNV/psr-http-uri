<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;

use HNV\Http\UriTests\CombinationsProvider\FullString\{
    SchemeCombinations,
    AuthorityCombinations,
    PathCombinations,
    QueryCombinations,
    FragmentCombinations
};

use function array_merge;
/** ***********************************************************************************************
 * URI full string different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FullString implements CombinationsProviderInterface
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
     *          value       => login:password@site.com:10,
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