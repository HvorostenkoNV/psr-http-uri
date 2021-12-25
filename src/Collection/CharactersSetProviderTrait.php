<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use function str_split;
use function array_unique;
/** ***********************************************************************************************
 * Characters parser helper.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
trait CharactersSetProviderTrait
{
    /** **********************************************************************
     * Parse strings set to unique characters set.
     *
     * @param   string[] $data              Strings set.
     *
     * @return  string[]                    Characters set.
     ************************************************************************/
    protected static function getUniqueSingleCharactersSet(array $data): array
    {
        $result = [];

        foreach ($data as $string) {
            foreach (str_split($string) as $char) {
                $result[] = $char;
            }
        }

        return array_unique($result);
    }
}
