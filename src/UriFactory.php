<?php

declare(strict_types=1);

namespace HNV\Http\Uri;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use InvalidArgumentException;
use Psr\Http\Message\{
    UriFactoryInterface,
    UriInterface,
};
use RuntimeException;

use function explode;
use function strlen;
use function strpos;
use function strrpos;
use function substr;

/**
 * PSR-7 UriFactoryInterface implementation.
 */
class UriFactory implements UriFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $uriOrigin        = $uri;
        $scheme           = $this->parseSchemeFromUri($uri);
        $fragment         = $this->parseFragmentFromUri($uri);
        $query            = $this->parseQueryFromUri($uri);
        $path             = $uri;
        $authority        = $this->parseAuthorityFromPath($path);
        $userInfo         = $this->parseUserInfoFromAuthority($authority);
        $userInfoExploded = explode(UriSubDelimiters::USER_INFO_SEPARATOR->value, $userInfo, 2);
        $userLogin        = $userInfoExploded[0];
        $userPassword     = $userInfoExploded[1] ?? '';
        $port             = $this->parsePortFromAuthority($authority);
        $host             = $authority;

        try {
            UriValidator::checkAuthorityIsValid($userInfo, $host, $port);
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException(
                "uri \"{$uriOrigin}\" has invalid authority",
                0,
                $exception
            );
        }

        try {
            UriValidator::checkUriIsValid($scheme, $host, $path);
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException(
                "uri \"{$uriOrigin}\" has not enough parts",
                0,
                $exception
            );
        }

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
            throw new InvalidArgumentException(
                "uri \"{$uriOrigin}\" building error",
                0,
                $exception
            );
        }
    }

    /**
     * Parse scheme from URI string.
     * URI string will be changed.
     */
    private function parseSchemeFromUri(string &$uri): string
    {
        $schemeDelimiterPosition = strpos($uri, UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value);
        $pathDelimiterPosition   = strpos($uri, UriSubDelimiters::PATH_PARTS_SEPARATOR->value);
        $ipV6FramePosition       = strpos($uri, UriGeneralDelimiters::IP_ADDRESS_V6_LEFT_FRAME->value);

        if ($schemeDelimiterPosition === false || $schemeDelimiterPosition === 0) {
            return '';
        }

        $pathDelimiterIsAfterScheme = $pathDelimiterPosition === false
            || $schemeDelimiterPosition < $pathDelimiterPosition;
        $ipV6FrameIsAfterScheme = $ipV6FramePosition === false
            || $schemeDelimiterPosition < $ipV6FramePosition;

        if ($pathDelimiterIsAfterScheme && $ipV6FrameIsAfterScheme) {
            $scheme = substr($uri, 0, $schemeDelimiterPosition);
            $uri    = substr($uri, $schemeDelimiterPosition + 1);

            return $scheme;
        }

        return '';
    }

    /**
     * Parse authority from path string.
     * Path string will be changed.
     */
    private function parseAuthorityFromPath(string &$path): string
    {
        $authority = '';

        if (str_starts_with($path, UriGeneralDelimiters::AUTHORITY_DELIMITER->value)) {
            $value                 = substr($path, strlen(UriGeneralDelimiters::AUTHORITY_DELIMITER->value));
            $pathDelimiterPosition = strpos($value, UriSubDelimiters::PATH_PARTS_SEPARATOR->value);

            if ($pathDelimiterPosition !== false) {
                $authority = substr($value, 0, $pathDelimiterPosition);
                $path      = substr($value, $pathDelimiterPosition);
            } else {
                $authority = $value;
                $path      = '';
            }
        }

        return $authority;
    }

    /**
     * Parse user info from authority string.
     * Authority string will be changed.
     */
    private function parseUserInfoFromAuthority(string &$authority): string
    {
        $delimiterPosition = strrpos($authority, UriGeneralDelimiters::USER_INFO_DELIMITER->value);

        if (str_contains($authority, UriGeneralDelimiters::USER_INFO_DELIMITER->value)) {
            $userInfo  = substr($authority, 0, $delimiterPosition);
            $authority = substr($authority, $delimiterPosition + 1);

            return $userInfo;
        }

        return '';
    }

    /**
     * Parse port from authority string.
     * Authority string will be changed.
     */
    private function parsePortFromAuthority(string &$authority): int
    {
        $portDelimiterPosition = strrpos($authority, UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value);
        $ipV6FramePosition     = strrpos($authority, UriGeneralDelimiters::IP_ADDRESS_V6_RIGHT_FRAME->value);
        $ipV6FrameIsBeforePort = $ipV6FramePosition === false
            || $portDelimiterPosition > $ipV6FramePosition;

        if ($portDelimiterPosition !== false && $ipV6FrameIsBeforePort) {
            $port      = (int) substr($authority, $portDelimiterPosition + 1);
            $authority = substr($authority, 0, $portDelimiterPosition);

            return $port;
        }

        return 0;
    }

    /**
     * Parse query from URI string.
     * URI string will be changed.
     */
    private function parseQueryFromUri(string &$uri): string
    {
        $delimiterPosition = strrpos($uri, UriGeneralDelimiters::QUERY_DELIMITER->value);

        if ($delimiterPosition !== false) {
            $query = substr($uri, $delimiterPosition + 1);
            $uri   = substr($uri, 0, $delimiterPosition);

            return $query;
        }

        return '';
    }

    /**
     * Parse fragment from URI string.
     * URI string will be changed.
     */
    private function parseFragmentFromUri(string &$uri): string
    {
        $delimiterPosition = strrpos($uri, UriGeneralDelimiters::FRAGMENT_DELIMITER->value);

        if ($delimiterPosition !== false) {
            $fragment = substr($uri, $delimiterPosition + 1);
            $uri      = substr($uri, 0, $delimiterPosition);

            return $fragment;
        }

        return '';
    }
}
