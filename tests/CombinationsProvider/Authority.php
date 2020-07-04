<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;

use HNV\Http\UriTests\CombinationsProvider\Authority\{
    UserInfoCombinations,
    HostCombinations,
    PortCombinations,
    SchemeWithPortCombinations
};

use function array_merge;
/** ***********************************************************************************************
 * URI authority different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Authority implements CombinationsProviderInterface
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
        $dataSets   = [
            UserInfoCombinations::get(),
            HostCombinations::get(),
            PortCombinations::get(),
            SchemeWithPortCombinations::get(),
        ];
        $result     = [];

        foreach ($dataSets as $dataSet) {
            $result = array_merge($result, $dataSet);
        }

        return $result;
    }
}