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
use function substr;
use function strpos;
use function strrpos;
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
        $uriOrigin          = $uri;
        $scheme             = $this->parseSchemeFromUri($uri);
        $fragment           = $this->parseFragmentFromUri($uri);
        $query              = $this->parseQueryFromUri($uri);
        $path               = $uri;
        $authority          = $this->parseAuthorityFromPath($path);
        $userInfo           = $this->parseUserInfoFromAuthority($authority);
        $userInfoExploded   = explode(UriSubDelimiters::USER_INFO_SEPARATOR, $userInfo, 2);
        $userLogin          = $userInfoExploded[0];
        $userPassword       = $userInfoExploded[1] ?? '';
        $port               = $this->parsePortFromAuthority($authority);
        $host               = $authority;

        //TODO replace to class
        $authorityInvalidConditions = [
            strlen($userLogin)  > 0 && strlen($host) === 0,
            $port               > 0 && strlen($host) === 0,
        ];
        $authorityIsValid           = true;

        foreach ($authorityInvalidConditions as $condition) {
            if ($condition) {
                $authorityIsValid = false;
                break;
            }
        }

        if (!$authorityIsValid) {
            throw new InvalidArgumentException("uri \"$uriOrigin\" has invalid authority");
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
            throw new InvalidArgumentException("uri \"$uriOrigin\" has not enough parts");
        }
        //TODO end

        try {
            return (new Uri())
                ->withScheme($scheme)
                ->withUserInfo($userLogin, $userPassword)
                ->withHost($host)
                ->withPort($port)
                ->withPath($path)
                ->withQuery($query)
                ->withFragment($fragment);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidArgumentException("uri \"$uriOrigin\" building error", 0, $exception);
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
            $scheme = substr($uri, 0, $schemeDelimiterPosition);
            $uri    = substr($uri, $schemeDelimiterPosition + 1);
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
            $value                  = substr($path, strlen(UriGeneralDelimiters::AUTHORITY_DELIMITER));
            $pathDelimiterPosition  = strpos($value, UriSubDelimiters::PATH_PARTS_SEPARATOR);

            if ($pathDelimiterPosition !== false) {
                $authority  = substr($value, 0, $pathDelimiterPosition);
                $path       = substr($value, $pathDelimiterPosition);
            } else {
                $authority  = $value;
                $path       = '';
            }
        }

        return $authority;
    }
    /** **********************************************************************
     * Parse user info from authority string.
     * Authority string will be changed.
     *
     * @param   string $authority           The authority string link.
     *
     * @return  string                      User info
     ************************************************************************/
    private function parseUserInfoFromAuthority(string &$authority): string
    {
        $userInfo           = '';
        $delimiterPosition  = strrpos($authority, UriGeneralDelimiters::USER_INFO_DELIMITER);

        if (str_contains($authority, UriGeneralDelimiters::USER_INFO_DELIMITER)) {
            $userInfo   = substr($authority, 0, $delimiterPosition);
            $authority  = substr($authority, $delimiterPosition + 1);
        }

        return $userInfo;
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
        $portDelimiterPosition  = strrpos($authority, UriGeneralDelimiters::PORT_DELIMITER);
        $ipV6FramePosition      = strrpos($authority, UriGeneralDelimiters::IP_ADDRESS_V6_RIGHT_FRAME);
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
        $query              = '';
        $delimiterPosition  = strrpos($uri, UriGeneralDelimiters::QUERY_DELIMITER);

        if ($delimiterPosition !== false) {
            $query  = substr($uri, $delimiterPosition + 1);
            $uri    = substr($uri, 0, $delimiterPosition);
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
        $fragment           = '';
        $delimiterPosition  = strrpos($uri, UriGeneralDelimiters::FRAGMENT_DELIMITER);

        if ($delimiterPosition !== false) {
            $fragment   = substr($uri, $delimiterPosition + 1);
            $uri        = substr($uri, 0, $delimiterPosition);
        }

        return $fragment;
    }
}