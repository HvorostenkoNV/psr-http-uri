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
        $scheme     = $this->parseSchemeFromUri($uri);
        $fragment   = $this->parseFragmentFromUri($uri);
        $query      = $this->parseQueryFromUri($uri);
        $path       = $uri;
        $authority  = $this->parseAuthorityFromPath($path);
        $userData   = $this->parseUserDataFromAuthority($authority);
        $port       = $this->parsePortFromAuthority($authority);
        $host       = $authority;

        //TODO replace to class
        $authorityInvalidConditions = [
            strlen($userData['login'])  > 0  && strlen($host) === 0,
            $port                       > 0  && strlen($host) === 0,
        ];
        $authorityIsValid           = true;

        foreach ($authorityInvalidConditions as $condition) {
            if ($condition) {
                $authorityIsValid = false;
                break;
            }
        }

        if (!$authorityIsValid) {
            throw new InvalidArgumentException("uri \"$uri\" has invalid authority");
        }
        //TODO end
        //TODO replace to class
        $uriValidConditions = [
            strlen($scheme)  >  0   && strlen($host)  >  0  && strlen($path)  >  0,
            strlen($scheme)  >  0   && strlen($host) === 0  && strlen($path)  >  0,
            strlen($scheme)  >  0   && strlen($host)  >  0  && strlen($path) === 0,
            strlen($scheme) === 0   && strlen($host) === 0  && strlen($path)  >  0,
        ];
        $uriIsValid         = false;

        foreach ($uriValidConditions as $condition) {
            if ($condition) {
                $uriIsValid = true;
                break;
            }
        }

        if (!$uriIsValid) {
            throw new InvalidArgumentException("uri \"$uri\" has not enough parts");
        }
        //TODO end

        try {
            return (new Uri())
                ->withScheme($scheme)
                ->withUserInfo($userData['login'], $userData['password'])
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
        $scheme                     = '';

        $pathDelimiterIsAfterScheme =
            $pathDelimiterPosition === false ||
            $schemeDelimiterPosition < $pathDelimiterPosition;
        $ipV6FrameIsAfterScheme     =
            $ipV6FramePosition === false ||
            $schemeDelimiterPosition < $ipV6FramePosition;

        if (
            $schemeDelimiterPosition !== false  &&
            $pathDelimiterIsAfterScheme         &&
            $ipV6FrameIsAfterScheme
        ) {
            $uriExploded    = explode(UriGeneralDelimiters::SCHEME_DELIMITER, $uri, 2);
            $scheme         = $uriExploded[0];
            $uri            = $uriExploded[1] ?? '';
        }

        return $scheme;
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
        $authority = '';

        if (str_starts_with($path, UriGeneralDelimiters::AUTHORITY_DELIMITER)) {
            $value = substr($path, strlen(UriGeneralDelimiters::AUTHORITY_DELIMITER));

            if (str_contains($value, UriSubDelimiters::PATH_PARTS_SEPARATOR)) {
                $valueExploded  = explode(UriSubDelimiters::PATH_PARTS_SEPARATOR, $value, 2);
                $authority      = $valueExploded[0];
                $path           = $valueExploded[1] ?? '';
            } else {
                $authority      = $value;
                $path           = '';
            }
        }

        return $authority;
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
        $userData = [
            'login'     => '',
            'password'  => '',
        ];

        if (str_contains($authority, UriGeneralDelimiters::USER_INFO_DELIMITER)) {
            $authorityExploded      = explode(UriGeneralDelimiters::USER_INFO_DELIMITER, $authority, 2);
            $userDataString         = $authorityExploded[0];
            $authority              = $authorityExploded[1] ?? '';
            $userDataExploded       = explode(UriSubDelimiters::USER_INFO_SEPARATOR, $userDataString, 2);

            $userData['login']      = $userDataExploded[0];
            $userData['password']   = $userDataExploded[1] ?? '';
        }

        return $userData;
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
        $portDelimiterPosition  = strripos($authority, UriGeneralDelimiters::PORT_DELIMITER);
        $ipV6FramePosition      = strripos($authority, UriGeneralDelimiters::IP_ADDRESS_V6_RIGHT_FRAME);
        $ipV6FrameIsBeforePort  =
            $ipV6FramePosition === false ||
            $portDelimiterPosition > $ipV6FramePosition;
        $port                   = 0;

        if ($portDelimiterPosition !== false && $ipV6FrameIsBeforePort) {
            $port       = (int) substr($authority, $portDelimiterPosition + 1);
            $authority  = substr($authority, 0, $portDelimiterPosition);
        }

        return $port;
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
        $query = '';

        if (str_contains($uri, UriGeneralDelimiters::QUERY_DELIMITER)) {
            $uriExploded    = explode(UriGeneralDelimiters::QUERY_DELIMITER, $uri, 2);
            $uri            = $uriExploded[0];
            $query          = $uriExploded[1] ?? '';
        }

        return $query;
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
        $fragment = '';

        if (str_contains($uri, UriGeneralDelimiters::FRAGMENT_DELIMITER)) {
            $uriExploded    = explode(UriGeneralDelimiters::FRAGMENT_DELIMITER, $uri, 2);
            $uri            = $uriExploded[0];
            $fragment       = $uriExploded[1] ?? '';
        }

        return $fragment;
    }
}