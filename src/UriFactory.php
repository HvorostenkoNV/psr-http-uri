<?php

declare(strict_types=1);

namespace HNV\Http\Uri;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    IpAddressV6Rules,
    PathRules,
    PortRules,
    QueryRules,
    SchemeRules,
    UserInfoRules,
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
        $userInfoExploded = explode(UserInfoRules::VALUES_SEPARATOR->value, $userInfo, 2);
        $userLogin        = $userInfoExploded[0];
        $userPassword     = $userInfoExploded[1] ?? '';
        $port             = $this->parsePortFromAuthority($authority);
        $host             = $authority;

        try {
            UriValidator::checkAuthorityIsValid($userInfo, $host, $port);
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException(
                "uri [{$uriOrigin}] has invalid authority",
                0,
                $exception
            );
        }

        try {
            UriValidator::checkUriIsValid($scheme, $host, $path);
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException(
                "uri [{$uriOrigin}] has not enough parts",
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
                "uri [{$uriOrigin}] building error",
                0,
                $exception
            );
        }
    }

    private function parseSchemeFromUri(string &$uri): string
    {
        $schemeDelimiterPosition = strpos($uri, SchemeRules::URI_DELIMITER->value);
        $pathDelimiterPosition   = strpos($uri, PathRules::PARTS_SEPARATOR->value);
        $ipV6FramePosition       = strpos($uri, IpAddressV6Rules::LEFT_FRAME->value);

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

    private function parseAuthorityFromPath(string &$path): string
    {
        $authority = '';

        if (str_starts_with($path, AuthorityRules::URI_DELIMITER)) {
            $value                 = substr($path, strlen(AuthorityRules::URI_DELIMITER));
            $pathDelimiterPosition = strpos($value, PathRules::PARTS_SEPARATOR->value);

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

    private function parseUserInfoFromAuthority(string &$authority): string
    {
        $delimiterPosition = strrpos($authority, UserInfoRules::URI_DELIMITER->value);

        if (str_contains($authority, UserInfoRules::URI_DELIMITER->value)) {
            $userInfo  = substr($authority, 0, $delimiterPosition);
            $authority = substr($authority, $delimiterPosition + 1);

            return $userInfo;
        }

        return '';
    }

    private function parsePortFromAuthority(string &$authority): int
    {
        $portDelimiterPosition = strrpos($authority, PortRules::URI_DELIMITER->value);
        $ipV6FramePosition     = strrpos($authority, IpAddressV6Rules::RIGHT_FRAME->value);
        $ipV6FrameIsBeforePort = $ipV6FramePosition === false
            || $portDelimiterPosition > $ipV6FramePosition;

        if ($portDelimiterPosition !== false && $ipV6FrameIsBeforePort) {
            $port      = (int) substr($authority, $portDelimiterPosition + 1);
            $authority = substr($authority, 0, $portDelimiterPosition);

            return $port;
        }

        return 0;
    }

    private function parseQueryFromUri(string &$uri): string
    {
        $delimiterPosition = strrpos($uri, QueryRules::URI_DELIMITER->value);

        if ($delimiterPosition !== false) {
            $query = substr($uri, $delimiterPosition + 1);
            $uri   = substr($uri, 0, $delimiterPosition);

            return $query;
        }

        return '';
    }

    private function parseFragmentFromUri(string &$uri): string
    {
        $delimiterPosition = strrpos($uri, FragmentRules::URI_DELIMITER->value);

        if ($delimiterPosition !== false) {
            $fragment = substr($uri, $delimiterPosition + 1);
            $uri      = substr($uri, 0, $delimiterPosition);

            return $fragment;
        }

        return '';
    }
}
