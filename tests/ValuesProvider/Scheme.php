<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\UriTests\Collection\SpecialCharacters;
use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    SchemeAllowedCharacters
};

use function strtolower;
use function strtoupper;
use function ucfirst;
use function array_diff;
/** ***********************************************************************************************
 * URI scheme values provider.
 *
 * @package HNV\Psr\Http\Tests
 * @author  Hvorostenko
 *************************************************************************************************/
class Scheme implements ValuesProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getValidValues(): array
    {
        $letter = 's';
        $digit  = 1;
        $string = 'scheme';
        $result = [];

        $data   = [
            $string,
            strtoupper($string),
            ucfirst($string),

            "$string$digit",
            "$string$digit$string",

            "$letter$letter",
            "$letter$digit",

            $letter.strtoupper($letter),
            strtoupper($letter).strtoupper($letter),
            strtoupper($letter).$letter,
        ];

        foreach (SchemeAllowedCharacters::get() as $char) {
            $data[] = "$string$char";
            $data[] = "$string$char$string";
        }

        foreach ($data as $value) {
            $result[$value] = strtolower($value);
        }

        return $result;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getInvalidValues(): array
    {
        $letter         = 's';
        $digit          = 1;
        $string         = 'scheme';

        $allowedChars   = SchemeAllowedCharacters::get();
        $otherChars     = array_diff(
            SpecialCharacters::get(),
            UriGeneralDelimiters::get(),
            $allowedChars
        );

        $result         = [
            "$string ",
            " $string",
            "$string $string",

            $letter,
            "$digit$letter",
            "$digit$string",
        ];

        foreach ($allowedChars as $char) {
            $result[]   = "$char$string";
        }
        foreach ($otherChars as $char) {
            $result[]   = "$char$string";
            $result[]   = "$string$char";
            $result[]   = "$string$char$string";
        }

        return $result;
    }
}