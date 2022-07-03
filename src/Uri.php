<?php

declare(strict_types=1);

namespace HNV\Http\Uri;

use HNV\Http\Helper\Normalizer\NormalizingException;
use HNV\Http\Uri\Collection\{
    SchemeStandardPorts,
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\Uri\Normalizer\{
    Fragment            as FragmentNormalizer,
    Host                as HostNormalizer,
    Path                as PathNormalizer,
    Port                as PortNormalizer,
    Query               as QueryNormalizer,
    Scheme              as SchemeNormalizer,
    UserInfo\Login      as UserLoginNormalizer,
    UserInfo\Password   as UserPasswordNormalizer,
};
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use RuntimeException;

use function is_null;
use function ltrim;
use function str_starts_with;
use function strlen;

/**
 * PSR-7 UriInterface implementation.
 */
class Uri implements UriInterface
{
    private string  $scheme   = '';
    private string  $host     = '';
    private ?int    $port     = null;
    private string  $user     = '';
    private string  $password = '';
    private string  $path     = '';
    private string  $query    = '';
    private string  $fragment = '';

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $scheme    = $this->getScheme();
        $authority = $this->getAuthority();
        $path      = $this->getPath();
        $query     = $this->getQuery();
        $fragment  = $this->getFragment();
        $result    = '';

        try {
            UriValidator::checkUriIsValid($scheme, $authority, $path);
        } catch (RuntimeException) {
            return '';
        }

        if (strlen($scheme) > 0) {
            $result .= $scheme.UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        }
        if (strlen($authority) > 0) {
            $result .= UriGeneralDelimiters::AUTHORITY_DELIMITER->value.$authority;
        }
        if (strlen($path) > 0) {
            $pathPrefix              = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
            $pathHasPrefix           = str_starts_with($path, $pathPrefix);
            $pathHasMultiplePrefixes = str_starts_with($path, $pathPrefix.$pathPrefix);

            if (strlen($authority) > 0 && !$pathHasPrefix) {
                $result .= $pathPrefix.$path;
            } elseif (strlen($authority) === 0 && $pathHasMultiplePrefixes) {
                $result .= $pathPrefix.ltrim($path, $pathPrefix);
            } else {
                $result .= $path;
            }
        }
        if (strlen($query) > 0) {
            $result .= UriGeneralDelimiters::QUERY_DELIMITER->value.$query;
        }
        if (strlen($fragment) > 0) {
            $result .= UriGeneralDelimiters::FRAGMENT_DELIMITER->value.$fragment;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function withScheme(string $scheme): static
    {
        try {
            $newInstance         = clone $this;
            $newInstance->scheme = strlen($scheme) > 0
                ? SchemeNormalizer::normalize($scheme)
                : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "scheme \"{$scheme}\" is invalid",
                0,
                $exception
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritDoc}
     */
    public function withUserInfo(string $user, string $password = ''): static
    {
        $newInstance = clone $this;

        try {
            $newInstance->user     = strlen($user) > 0
                ? UserLoginNormalizer::normalize($user)
                : '';
            $newInstance->password = strlen($password) > 0 && strlen($user) > 0
                ? UserPasswordNormalizer::normalize($password)
                : '';
        } catch (NormalizingException) {
            $newInstance->user     = '';
            $newInstance->password = '';
        }

        return $newInstance;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInfo(): string
    {
        return strlen($this->user) > 0 && strlen($this->password) > 0
            ? $this->user.UriSubDelimiters::USER_INFO_SEPARATOR->value.$this->password
            : $this->user;
    }

    /**
     * {@inheritDoc}
     */
    public function withHost(string $host): static
    {
        try {
            $newInstance       = clone $this;
            $newInstance->host = strlen($host) > 0 ? HostNormalizer::normalize($host) : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException("host \"{$host}\" is invalid", 0, $exception);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function withPort(int $port = 0): static
    {
        try {
            $portNormalized = $port !== 0 ? PortNormalizer::normalize($port) : 0;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException("port \"{$port}\" is invalid", 0, $exception);
        }

        $newInstance    = clone $this;
        $portIsStandard = SchemeStandardPorts::findByPort($portNormalized);

        $newInstance->port = $portNormalized === 0 || $portIsStandard ? null : $portNormalized;

        return $newInstance;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthority(): string
    {
        $userInfo = $this->getUserInfo();
        $host     = $this->getHost();
        $port     = $this->getPort();
        $result   = '';

        try {
            UriValidator::checkAuthorityIsValid($userInfo, $host, $port ?? 0);
        } catch (RuntimeException) {
            return '';
        }

        if (strlen($userInfo) > 0) {
            $result .= $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER->value;
        }
        $result .= $host;
        if (!is_null($port)) {
            $result .= UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value.$port;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath(string $path): static
    {
        try {
            $newInstance       = clone $this;
            $newInstance->path = strlen($path) > 0 ? PathNormalizer::normalize($path) : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException("path \"{$path}\" is invalid", 0, $exception);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function withQuery(string $query): static
    {
        try {
            $newInstance        = clone $this;
            $newInstance->query = strlen($query) > 0 ? QueryNormalizer::normalize($query) : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException("query \"{$query}\" is invalid", 0, $exception);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function withFragment(string $fragment): static
    {
        $newInstance = clone $this;

        try {
            $newInstance->fragment = strlen($fragment) > 0
                ? FragmentNormalizer::normalize($fragment)
                : '';
        } catch (NormalizingException) {
            $newInstance->fragment = '';
        }

        return $newInstance;
    }

    /**
     * {@inheritDoc}
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }
}
