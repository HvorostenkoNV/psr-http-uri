<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator;

use HNV\Http\Helper\Generator\GeneratorInterface;
use HNV\Http\Uri\Collection\{
    PathRules,
    PortRules,
    UserInfoRules,
};
use HNV\Http\UriTests\Generator\{
    Fragment\NormalizedValuesGenerator          as FragmentNormalizedValuesGenerator,
    Host\NormalizedValuesGenerator              as HostNormalizedValuesGenerator,
    Path\NormalizedValuesGenerator              as PathNormalizedValuesGenerator,
    Port\NormalizedValuesGenerator              as PortNormalizedValuesGenerator,
    Query\NormalizedValuesGenerator             as QueryNormalizedValuesGenerator,
    Scheme\NormalizedValuesGenerator            as SchemeNormalizedValuesGenerator,
    UserInfo\LoginNormalizedValuesGenerator     as UserLoginNormalizedValuesGenerator,
    UserInfo\PasswordNormalizedValuesGenerator  as UserPasswordNormalizedValuesGenerator,
};

use function str_starts_with;
use function strlen;

abstract class AbstractCombinationsGenerator implements GeneratorInterface
{
    protected string $scheme;
    protected string $schemeNormalized;
    protected string $login;
    protected string $loginNormalized;
    protected string $password;
    protected string $passwordNormalized;
    protected string $userInfo;
    protected string $userInfoNormalized;
    protected string $host;
    protected string $hostNormalized;
    protected int    $port;
    protected int    $portNormalized;
    protected string $authority;
    protected string $authorityNormalized;
    protected string $path;
    protected string $pathNormalized;
    protected string $query;
    protected string $queryNormalized;
    protected string $fragment;
    protected string $fragmentNormalized;

    public function __construct()
    {
        $this->initializeDefaultSchemeData();
        $this->initializeDefaultUserData();
        $this->initializeDefaultHostData();
        $this->initializeDefaultPortData();
        $this->initializeDefaultPathData();
        $this->initializeDefaultQueryData();
        $this->initializeDefaultFragmentData();

        $this->userInfo = $this->login
            .UserInfoRules::VALUES_SEPARATOR->value
            .$this->password;
        $this->userInfoNormalized = $this->loginNormalized
            .UserInfoRules::VALUES_SEPARATOR->value
            .$this->passwordNormalized;

        $this->authority = $this->userInfo
            .UserInfoRules::URI_DELIMITER->value
            .$this->host
            .PortRules::URI_DELIMITER->value
            .$this->port;
        $this->authorityNormalized = $this->userInfoNormalized
            .UserInfoRules::URI_DELIMITER->value
            .$this->hostNormalized
            .PortRules::URI_DELIMITER->value
            .$this->portNormalized;
    }

    private function initializeDefaultSchemeData(): void
    {
        foreach ((new SchemeNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->scheme           = $value->value;
                $this->schemeNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultUserData(): void
    {
        foreach ((new UserLoginNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->login           = $value->value;
                $this->loginNormalized = $value->valueNormalized;
                break;
            }
        }

        foreach ((new UserPasswordNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->password           = $value->value;
                $this->passwordNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultHostData(): void
    {
        foreach ((new HostNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->host           = $value->value;
                $this->hostNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultPortData(): void
    {
        foreach ((new PortNormalizedValuesGenerator())->generate() as $value) {
            if (
                $value->value > PortRules::MIN_VALUE
                && $value->valueNormalized > PortRules::MIN_VALUE
            ) {
                $this->port           = $value->value;
                $this->portNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultPathData(): void
    {
        foreach ((new PathNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
                && !str_starts_with($value->value, PathRules::PARTS_SEPARATOR->value)
            ) {
                $this->path           = $value->value;
                $this->pathNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultQueryData(): void
    {
        foreach ((new QueryNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->query           = $value->value;
                $this->queryNormalized = $value->valueNormalized;
                break;
            }
        }
    }

    private function initializeDefaultFragmentData(): void
    {
        foreach ((new FragmentNormalizedValuesGenerator())->generate() as $value) {
            if (
                strlen($value->value) > 0
                && strlen($value->valueNormalized) > 0
            ) {
                $this->fragment           = $value->value;
                $this->fragmentNormalized = $value->valueNormalized;
                break;
            }
        }
    }
}
