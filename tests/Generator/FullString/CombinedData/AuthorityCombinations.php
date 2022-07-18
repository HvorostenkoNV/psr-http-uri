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
use HNV\Http\UriTests\Generator\AbstractCombinationsGenerator;
use HNV\Http\UriTests\Generator\Authority\CombinedDataGenerator as AuthorityCombinedDataGenerator;
use HNV\Http\UriTests\ValueObject\{
    Authority\CombinedData              as AuthorityCombinedData,
    Authority\CombinedDataWithScheme    as AuthorityCombinedDataWithScheme,
    Authority\Data                      as AuthorityData,
    FullString\CombinedData             as FullStringCombinedData,
    UserInfo\Data                       as UserInfoData,
};

use function strlen;

class AuthorityCombinations extends AbstractCombinationsGenerator
{
    /** @var AuthorityCombinedDataWithScheme[] */
    private array $authorityValidCombinations                = [];

    /** @var AuthorityCombinedDataWithScheme[] */
    private array $authorityInvalidCombinations              = [];

    /** @var AuthorityCombinedData[] */
    private array $authorityWithoutSchemeValidCombinations   = [];

    /** @var AuthorityCombinedData[] */
    private array $authorityWithoutSchemeInvalidCombinations = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new AuthorityCombinedDataGenerator())->generate() as $combination) {
            $hasScheme              = strlen($combination->scheme) > 0;
            $valueIsValid           = strlen($combination->fullValue) > 0;
            $combinationWithScheme  = new AuthorityCombinedDataWithScheme(
                strlen($combination->scheme) > 0 ? $combination->scheme : $this->scheme,
                new UserInfoData($combination->userLogin, $combination->userPassword),
                $combination->host,
                $combination->port,
                strlen($combination->scheme) > 0 ? $combination->scheme : $this->schemeNormalized,
                $combination->fullValue
            );

            $valueIsValid
                ? $this->authorityValidCombinations[]   = $combinationWithScheme
                : $this->authorityInvalidCombinations[] = $combinationWithScheme;

            if (!$hasScheme) {
                $valueIsValid
                    ? $this->authorityWithoutSchemeValidCombinations[]   = $combination
                    : $this->authorityWithoutSchemeInvalidCombinations[] = $combination;
            }
        }
    }

    /**
     * @return FullStringCombinedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutScheme();
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

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$this->pathNormalized.$queryDelimiter.$this->queryNormalized.
                $fragmentDelimiter.$this->fragmentNormalized,
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->authorityWithoutSchemeValidCombinations as $combination) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                '',
                '',
            );
        }

        foreach ($this->authorityWithoutSchemeInvalidCombinations as $combination) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                $this->fragment,
                $this->pathNormalized.$queryDelimiter
                .$this->queryNormalized.$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                $this->fragment,
                $this->pathNormalized.$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                '',
                $this->pathNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
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

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue,
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                '',
                '',
                '',
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                '',
                $this->query,
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

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$pathDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$pathDelimiter.$this->pathNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                $this->fragment,
                $combination->schemeNormalized
                .$schemeDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                '',
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$this->pathNormalized,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->authorityValidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$authorityDelimiter.$combination->fullValue
                .$pathDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->authorityInvalidCombinations as $combination) {
            yield new FullStringCombinedData(
                $combination->scheme,
                new AuthorityData(
                    new UserInfoData($combination->userLogin, $combination->userPassword),
                    $combination->host,
                    $combination->port,
                ),
                $this->path,
                $this->query,
                '',
                $combination->schemeNormalized
                .$schemeDelimiter.$this->pathNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }
    }
}
