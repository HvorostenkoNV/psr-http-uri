<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\CombinationsProvider\UserInfo\CombinedValue as UserInfoCombinationsProvider;
use PHPUnit\Framework\{
    Attributes,
    TestCase,
};

use function spl_object_id;
use function strlen;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Medium]
class UriUserInfoTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withUserInfoProvidesNewInstance(string $login): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withUserInfo($login);
        $uriThird  = $uriSecond->withUserInfo($login);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            'Expects instance not the same'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            'Expects instance not the same'
        );
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getUserInfo(
        string $login,
        string $password,
        string $valueNormalized
    ): void {
        $valueCaught = (new Uri())->withUserInfo($login, $password)->getUserInfo();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getUserInfoOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getUserInfo();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getUserInfoAfterClear(string $login): void
    {
        $valueCaught = (new Uri())
            ->withUserInfo($login)
            ->withUserInfo('')
            ->getUserInfo();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function withUserInfoWithInvalidValueClearsPreviousValue(
        string $validValue,
        string $login,
        string $password
    ): void {
        $valueCaught = (new Uri())
            ->withUserInfo($validValue)
            ->withUserInfo($login, $password)
            ->getUserInfo();

        static::assertSame('', $valueCaught);
    }

    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            $result[] = [
                $combination['login'],
                $combination['password'],
                $combination['value'],
            ];
        }

        return $result;
    }

    public function dataProviderValidWithInvalidValues(): array
    {
        $normalizedValues = $this->dataProviderNormalizedValues();
        $validLogin       = '';
        $result           = [];

        foreach ($normalizedValues as $dataSet) {
            $login = $dataSet[0];

            if (strlen($login) > 0) {
                $validLogin = $login;
                break;
            }
        }

        foreach ($normalizedValues as $dataSet) {
            $login    = $dataSet[0];
            $password = $dataSet[1];
            $userInfo = $dataSet[2];

            if (strlen($userInfo) === 0) {
                $result[] = [$validLogin, $login, $password];
            }
        }

        return $result;
    }
}
