<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CollectionInterface;

use function array_merge;
use function array_diff;
/** ***********************************************************************************************
 * URI sub delimiters collection.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriSubDelimiters implements CollectionInterface
{
    use CharactersSetProviderTrait;

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

    private static array $collection = [];
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
            $generalDelimiters  = UriGeneralDelimiters::get();
            $separatorsChars    = self::getUniqueSingleCharactersSet($separators);
            self::$collection   = array_diff($separatorsChars, $generalDelimiters);
        }

        return self::$collection;
    }
}
