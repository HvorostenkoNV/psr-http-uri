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
    Scheme\InvalidValuesGenerator       as SchemeInvalidValuesGenerator,
    Scheme\NormalizedValuesGenerator    as SchemeNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\Data          as AuthorityData,
    FullString\CombinedData as FullStringCombinedData,
    InvalidValue,
    NormalizedValue,
    UserInfo\Data           as UserInfoData,
};

class SchemeCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $normalizedScheme = [];

    /** @var InvalidValue[] */
    private array $invalidScheme    = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new SchemeNormalizedValuesGenerator())->generate() as $value) {
            $this->normalizedScheme[] = $value;
        }

        foreach ((new SchemeInvalidValuesGenerator())->generate() as $value) {
            $this->invalidScheme[] = $value;
        }
    }

    /**
     * @return FullStringCombinedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutAuthority();
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutQuery();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->invalidScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                '',
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                '',
                $value->valueNormalized.$schemeDelimiter.$this->pathNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                '',
                $value->valueNormalized.$schemeDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->invalidScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                $this->fragment,
                $this->pathNormalized.$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                '',
                '',
                $this->pathNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $this->query,
                '',
                $this->pathNormalized.$queryDelimiter.$this->queryNormalized,
            );
        }
    }

    private function getValuesWithoutPath(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized,
            );
        }

        foreach ($this->invalidScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                '',
                '',
                '',
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $this->fragment,
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized,
            );
        }

        foreach ($this->invalidScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                '',
                '',
                '',
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                '',
                $value->valueNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->invalidScheme as $value) {
            yield new FullStringCombinedData(
                $value->value,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $this->query,
                '',
                '',
            );
        }
    }
}
