<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use function str_split;
use function array_merge;
use function array_unique;
use function array_diff;
/** ***********************************************************************************************
 * URI sub delimiters collection.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriSubDelimiters implements CollectionInterface
{
    public const USER_INFO_SEPARATOR            = ':';
    public const PATH_PARTS_SEPARATOR           = '/';
    public const QUERY_FIELDS_SEPARATOR         = '&';
    public const QUERY_FIELD_VALUE_SEPARATOR    = '=';

    private const OTHER_SUB_DELIMITERS = [
        '!',
        '$',
        '\'',
        '(',
        ')',
        '*',
        '+',
        ',',
        ';',
    ];

    private static $collection = [];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        if (count(self::$collection) === 0) {
            $separators         = array_merge(
                [
                    self::USER_INFO_SEPARATOR,
                    self::PATH_PARTS_SEPARATOR,
                    self::QUERY_FIELDS_SEPARATOR,
                    self::QUERY_FIELD_VALUE_SEPARATOR,
                ],
                self::OTHER_SUB_DELIMITERS
            );
            $separatorsChars    = [];
            $generalDelimiters  = UriGeneralDelimiters::get();

            foreach ($separators as $value) {
                foreach (str_split($value) as $char) {
                    $separatorsChars[] = $char;
                }
            }

            $allSubDelimiters   = array_unique($separatorsChars);
            self::$collection   = array_diff($allSubDelimiters, $generalDelimiters);
        }

        return self::$collection;
    }
}