<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;
use HNV\Http\Uri\Uri;

use function spl_object_id;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with fragment values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriFragmentTest extends TestCase
{
    /** **********************************************************************
     * Test "Uri::withFragment" provides new instance of URI.
     *
     * @covers          Uri::withFragment
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string $value               Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst   = new Uri();
        $uriSecond  = $uriFirst->withFragment($value);
        $uriThird   = $uriSecond->withFragment($value);

        self::assertNotEquals(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withFragment\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
        self::assertNotEquals(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withFragment\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getFragment" provides valid normalized value.
     *
     * @covers          Uri::getFragment
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string  $value              Value.
     * @param           string  $valueNormalized    Normalized value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withFragment($value)->getFragment();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (value => $value).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getFragment" provides expects value from empty object.
     *
     * @covers  Uri::getFragment
     *
     * @return  void
     * @throws  Throwable
     ************************************************************************/
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getFragment();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->getFragment\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withFragment" clears value on setting empty string.
     *
     * @covers          Uri::withFragment
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string $value               Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withFragment($value)
            ->withFragment('')
            ->getFragment();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->withFragment->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (login => $value, login => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withFragment" clears value on setting incorrect data.
     *
     * @covers          Uri::withFragment
     * @dataProvider    dataProviderValidWithInvalidValues
     *
     * @param           string  $validValue         Valid value.
     * @param           string  $invalidValue       Invalid value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testClearValueWithInvalidData(string $validValue, string $invalidValue): void
    {
        $valueCaught = (new Uri())
            ->withFragment($validValue)
            ->withFragment($invalidValue)
            ->getFragment();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->withFragment->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (value => $validValue, value => $invalidValue).\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Data provider: values with their normalized pairs.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [(string) $value, $valueNormalized];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: invalid values.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: valid values with invalid values.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderValidWithInvalidValues(): array
    {
        $validValues    = $this->dataProviderNormalizedValues();
        $invalidValues  = $this->dataProviderInvalidValues();
        $result         = [];

        foreach ($invalidValues as $data) {
            $result[] = [
                $validValues[0][0],
                $validValues[0][1],
                $data[0]
            ];
        }

        return $result;
    }
}
