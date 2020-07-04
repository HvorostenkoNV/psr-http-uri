<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\ValuesProvider\Port as PortValuesProvider;
use HNV\Http\Uri\{
    Uri,
    Collection\SchemeStandardPorts
};

use function spl_object_id;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with port values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriPortTest extends TestCase
{
    /** **********************************************************************
     * Test "Uri::withPort" provides new instance of URI.
     *
     * @covers          Uri::withPort
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           int $value                  Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testProvidesNewInstance(int $value): void
    {
        $uriFirst   = new Uri();
        $uriSecond  = $uriFirst->withPort($value);
        $uriThird   = $uriSecond->withPort($value);

        self::assertNotEquals(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withPort\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
        self::assertNotEquals(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withPort\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getPort" provides valid normalized value.
     *
     * @covers          Uri::getPort
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           int $value                  Value.
     * @param           int $valueNormalized        Normalized value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testGetValue(int $value, int $valueNormalized): void
    {
        $valueCaught = (new Uri())->withPort($value)->getPort();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (value => $value).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getPort" provides expects value from empty object.
     *
     * @covers  Uri::getPort
     *
     * @return  void
     * @throws  Throwable
     ************************************************************************/
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getPort();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->getPort\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getPort" provides null if port is standard for given scheme.
     *
     * @covers          Uri::getPort
     * @dataProvider    dataProviderSchemeWithStandardPorts
     *
     * @param           string  $scheme             Scheme.
     * @param           int     $port               Standard port fo this scheme.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testGetValueWithStandardPort(string $scheme, int $port): void
    {
        $valueCaught = (new Uri())
            ->withScheme($scheme)
            ->withPort($port)
            ->getPort();

        self::assertNull(
            $valueCaught,
            "Action \"Uri->withScheme->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (scheme => $scheme, port => $port).\n".
            "Expected result is \"null\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withPort" clears value on setting empty string.
     *
     * @covers          Uri::withPort
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           int $value                  Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testClearValue(int $value): void
    {
        $valueCaught = (new Uri())
            ->withPort($value)
            ->withPort(0)
            ->getPort();

        self::assertEquals(
            null,
            $valueCaught,
            "Action \"Uri->withPort->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (value => $value, value => \"0\").\n".
            "Expected result is \"null\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withPort" throws exception with invalid argument.
     *
     * @covers          Uri::withPort
     * @dataProvider    dataProviderInvalidValues
     *
     * @param           int $value                  Invalid value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testThrowsException(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withPort($value);

        self::fail(
            "Action \"Uri->withPort\" threw no expected exception.\n".
            "Action was called with parameters (value => $value).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            "Caught no exception."
        );
    }
    /** **********************************************************************
     * Test "Uri::withPort" saves previous value with setting new invalid value.
     *
     * @covers          Uri::withPort
     * @dataProvider    dataProviderValidWithInvalidValues
     *
     * @param           int $value                  Valid value.
     * @param           int $valueNormalized        Normalized valid value.
     * @param           int $invalidValue           Invalid value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testSavesPreviousValueOnError(
        int $value,
        int $valueNormalized,
        int $invalidValue
    ): void {
        $uri = (new Uri())->withPort($value);

        try {
            $uri->withPort($invalidValue);
        } catch (InvalidArgumentException $exception) {

        }

        $valueCaught = $uri->getPort();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPort->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (value => $value (valid value),".
            " value => $invalidValue (invalid value)).\n".
            "Expected result is \"$valueNormalized\".\n".
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

        foreach (PortValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
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

        foreach (PortValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: schemes with standard ports.
     *
     * @return  array                                   Data.
     ************************************************************************/
    public function dataProviderSchemeWithStandardPorts(): array
    {
        $result = [];

        foreach (SchemeStandardPorts::get() as $port => $scheme) {
            $result[] = [$scheme, $port];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: valid values (with their normalized pairs) with invalid values.
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