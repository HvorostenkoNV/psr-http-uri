<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\FullString\CombinedData;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Fragment\InvalidValuesGenerator     as FragmentInvalidValuesGenerator,
    Fragment\NormalizedValuesGenerator  as FragmentNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\Data          as AuthorityData,
    FullString\CombinedData as FullStringCombinedData,
    InvalidValue,
    NormalizedValue,
    UserInfo\Data           as UserInfoData,
};

use function strlen;

class FragmentCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $normalizedFragments  = [];

    /** @var InvalidValue[] */
    private array $invalidFragments     = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new FragmentNormalizedValuesGenerator())->generate() as $value) {
            $this->normalizedFragments[] = new NormalizedValue(
                $value->value,
                strlen($value->valueNormalized) > 0
                    ? FragmentRules::URI_DELIMITER->value.$value->valueNormalized
                    : ''
            );
        }

        foreach ((new FragmentInvalidValuesGenerator())->generate() as $value) {
            $this->invalidFragments[] = $value;
        }
    }

    /**
     * @return FullStringCombinedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutScheme();
        yield from $this->getValuesWithoutAuthority();
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutQuery();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $value->value,
                '',
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $pathDelimiter  = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedFragments as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $value->value,
                $this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$value->valueNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $value->value,
                $this->pathNormalized
                .$value->valueNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $value->value,
                '',
            );
        }

        foreach ($this->invalidFragments as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $value->value,
                $this->pathNormalized.$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $value->value,
                $pathDelimiter.$this->pathNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $value->value,
                '',
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter = SchemeRules::URI_DELIMITER->value;
        $queryDelimiter  = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$this->pathNormalized.$queryDelimiter.$this->queryNormalized
                .$value->valueNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$this->pathNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$this->pathNormalized.$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $value->value,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter.$this->pathNormalized,
            );
        }
    }

    private function getValuesWithoutPath(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized
                .$value->valueNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized,
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;

        foreach ($this->normalizedFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidFragments as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $value->value,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized,
            );
        }
    }
}
