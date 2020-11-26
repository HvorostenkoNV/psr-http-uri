<?php
declare(strict_types=1);

namespace HNV\Http\Uri;

use InvalidArgumentException;
use Psr\Http\Message\{
    UriInterface,
    UriFactoryInterface
};
use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters
};

use function strlen;
use function strpos;
use function substr;
use function strripos;
use function explode;
/** ***********************************************************************************************
 * PSR-7 UriFactoryInterface implementation.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriFactory implements UriFactoryInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function createUri(string $uri = ''): UriInterface
    {
        $scheme         = $this->parseSchemeFromUri($uri);
        $fragment       = $this->parseFragmentFromUri($uri);
        $query          = $this->parseQueryFromUri($uri);
        $path           = $uri;
        $authority      = $this->parseAuthorityFromPath($path);
        $authorityCopy  = $authority;
        $userData       = $this->parseUserDataFromAuthority($authorityCopy);
        $port           = $this->parsePortFromAuthority($authorityCopy);
        $host           = $authorityCopy;

        if (
            !(strlen($scheme) > 0 && strlen($authority) > 0 && strlen($path) > 0)   &&
            !(strlen($scheme) > 0 && strlen($path) > 0)                             &&
            !(strlen($path) > 0)
        ) {
            throw new InvalidArgumentException("uri \"$uri\" has not enough parts");
        }

        try {
            return (new Uri())
                ->withScheme($scheme)
                ->withUserInfo($userData[0], $userData[1])
                ->withHost($host)
                ->withPort($port)
                ->withPath($path)
                ->withQuery($query)
                ->withFragment($fragment);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidArgumentException("uri \"$uri\" building error", 0, $exception);
        }
    }
    /** **********************************************************************
     * Parse scheme from URI string.
     * URI string will be changed.
     *
     * @param   string $uri                 The URI string link.
     *
     * @return  string                      Scheme.
     ************************************************************************/
    private function parseSchemeFromUri(string &$uri): string
    {
        $schemeDelimiterPosition    = strpos($uri, UriGeneralDelimiters::SCHEME_DELIMITER);
        $pathDelimiterPosition      = strpos($uri, UriSubDelimiters::PATH_PARTS_SEPARATOR);
        $ipV6FramePosition          = strpos($uri, UriGeneralDelimiters::IP_ADDRESS_V6_LEFT_FRAME);

        if (
            $schemeDelimiterPosition !== false && (
                $pathDelimiterPosition === false ||
                $schemeDelimiterPosition < $pathDelimiterPosition
            ) && (
                $ipV6FramePosition === false ||
                $schemeDelimiterPosition < $ipV6FramePosition
            )
        ) {
            $uriExploded    = explode(UriGeneralDelimiters::SCHEME_DELIMITER, $uri, 2);
            $uri            = $uriExploded[1] ?? '';

            return $uriExploded[0];
        }

        return '';
    }
    /** **********************************************************************
     * Parse authority from path string.
     * Path string will be changed.
     *
     * @param   string $path                The path string link.
     *
     * @return  string                      Authority.
     ************************************************************************/
    private function parseAuthorityFromPath(string &$path): string
    {
        if (strpos($path, UriGeneralDelimiters::AUTHORITY_DELIMITER) === 0) {
            $path = substr($path, strlen(UriGeneralDelimiters::AUTHORITY_DELIMITER));

            if (strpos($path, UriSubDelimiters::PATH_PARTS_SEPARATOR) !== false) {
                $pathExploded   = explode(UriSubDelimiters::PATH_PARTS_SEPARATOR, $path, 2);
                $path           = UriSubDelimiters::PATH_PARTS_SEPARATOR.($pathExploded[1] ?? '');

                return $pathExploded[0];
            } else {
                $authority  = $path;
                $path       = '';

                return $authority;
            }
        }

        return '';
    }
    /** **********************************************************************
     * Parse user data from authority string.
     * Authority string will be changed.
     *
     * @param   string $authority           The authority string link.
     *
     * @return  array                       User data array, login and password.
     ************************************************************************/
    private function parseUserDataFromAuthority(string &$authority): array
    {
        if (strpos($authority, UriGeneralDelimiters::USER_INFO_DELIMITER) !== false) {
            $authorityExploded  = explode(UriGeneralDelimiters::USER_INFO_DELIMITER, $authority, 2);
            $authority          = $authorityExploded[1] ?? '';
            $userDataString     = $authorityExploded[0];

            return explode(UriSubDelimiters::USER_INFO_SEPARATOR, $userDataString, 2);
        }

        return [
            '',
            ''
        ];
    }
    /** **********************************************************************
     * Parse port from authority string.
     * Authority string will be changed.
     *
     * @param   string $authority           The authority string link.
     *
     * @return  int                         Port.
     ************************************************************************/
    private function parsePortFromAuthority(string &$authority): int
    {
        $colonCharLastPosition  = strripos($authority, UriGeneralDelimiters::PORT_DELIMITER);
        $bracerCharLastPosition = strripos($authority, UriGeneralDelimiters::IP_ADDRESS_V6_RIGHT_FRAME);

        if (
            $colonCharLastPosition !== false && (
                $bracerCharLastPosition === false ||
                $colonCharLastPosition > $bracerCharLastPosition
            )
        ) {
            $port       = (int) substr($authority, $colonCharLastPosition + 1);
            $authority  = substr($authority, 0, $colonCharLastPosition);

            return $port;
        }

        return 0;
    }
    /** **********************************************************************
     * Parse query from URI string.
     * URI string will be changed.
     *
     * @param   string $uri                 The URI string link.
     *
     * @return  string                      Query.
     ************************************************************************/
    private function parseQueryFromUri(string &$uri): string
    {
        if (strpos($uri, UriGeneralDelimiters::QUERY_DELIMITER) !== false) {
            $uriExploded    = explode(UriGeneralDelimiters::QUERY_DELIMITER, $uri, 2);
            $uri            = $uriExploded[0];

            return $uriExploded[1] ?? '';
        }

        return '';
    }
    /** **********************************************************************
     * Parse fragment from URI string.
     * URI string will be changed.
     *
     * @param   string $uri                 The URI string link.
     *
     * @return  string                      Fragment.
     ************************************************************************/
    private function parseFragmentFromUri(string &$uri): string
    {
        if (strpos($uri, UriGeneralDelimiters::FRAGMENT_DELIMITER) !== false) {
            $uriExploded    = explode(UriGeneralDelimiters::FRAGMENT_DELIMITER, $uri, 2);
            $uri            = $uriExploded[0];

            return $uriExploded[1] ?? '';
        }

        return '';
    }
}