<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\UserInfo\CombinedDataGenerator;
use HNV\Http\UriTests\ValueObject\UserInfo\CombinedData as UserInfoCombinedData;
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
    public function withUserInfoProvidesNewInstance(UserInfoCombinedData $data): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withUserInfo($data->login);
        $uriThird  = $uriSecond->withUserInfo($data->login);

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
    public function getUserInfo(UserInfoCombinedData $data): void
    {
        $valueCaught = (new Uri())
            ->withUserInfo($data->login, $data->password)
            ->getUserInfo();

        static::assertSame($data->fullValue, $valueCaught);
    }

    #[Attributes\Test]
    public function getUserInfoOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getUserInfo();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getUserInfoAfterClear(UserInfoCombinedData $data): void
    {
        $valueCaught = (new Uri())
            ->withUserInfo($data->login)
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

    public function dataProviderNormalizedValues(): iterable
    {
        foreach ((new CombinedDataGenerator())->generate() as $combination) {
            yield [$combination];
        }
    }

    public function dataProviderValidWithInvalidValues(): iterable
    {
        $normalizedValues = $this->dataProviderNormalizedValues();
        $validLogin       = null;

        foreach ($normalizedValues as $dataSet) {
            /** @var UserInfoCombinedData $userData */
            $userData = $dataSet[0];

            if (strlen($userData->login) > 0) {
                $validLogin = $userData->login;
                break;
            }
        }

        foreach ($normalizedValues as $dataSet) {
            /** @var UserInfoCombinedData $userData */
            $userData = $dataSet[0];

            if (strlen($userData->fullValue) === 0) {
                yield [$validLogin, $userData->login, $userData->password];
            }
        }
    }
}
