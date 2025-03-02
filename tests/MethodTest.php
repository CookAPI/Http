<?php

declare(strict_types=1);

use Cook\Component\Http\Method;
use Cook\Component\Http\Exception\BadRequestException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Method class.
 */
final class MethodTest extends TestCase
{
    private Method $method;

    protected function setUp(): void
    {
        $this->method = new Method();
    }

    /**
     * Test setting and getting a valid scalar value.
     * @throws BadRequestException
     */
    public function testSetAndGetValidScalarValue(): void
    {
        $this->method->set('testKey', 'testValue');
        $this->assertSame('testValue', $this->method->get('testKey'));
    }

    /**
     * Test setting and getting a valid array value.
     * @throws BadRequestException
     */
    public function testSetAndGetValidArrayValue(): void
    {
        $arrayValue = ['key1' => 'value1', 'key2' => 'value2'];
        $this->method->set('arrayKey', $arrayValue);
        $this->assertSame($arrayValue, $this->method->get('arrayKey'));
    }

    /**
     * Test setting and getting a valid Stringable object.
     * @throws BadRequestException
     */
    public function testSetAndGetValidStringableValue(): void
    {
        $stringable = new class implements \Stringable {
            public function __toString(): string
            {
                return 'stringable_value';
            }
        };

        $this->method->set('stringableKey', $stringable);
        $this->assertSame('stringable_value', (string) $this->method->get('stringableKey'));
    }

    /**
     * Test retrieving a non-existent key with a default value.
     * @throws BadRequestException
     */
    public function testGetWithDefaultValue(): void
    {
        $this->assertSame('default', $this->method->get('nonExistentKey', 'default'));
    }

    /**
     * Test setting an invalid value and expecting an exception.
     */
    public function testSetInvalidValueThrowsException(): void
    {
        $this->expectException(BadRequestException::class);
        $this->method->set('invalidKey', new \stdClass());
    }

    /**
     * Test getting an invalid default value and expecting an exception.
     */
    public function testGetInvalidDefaultValueThrowsException(): void
    {
        $this->expectException(BadRequestException::class);
        $this->method->get('missingKey', new \stdClass());
    }

    /**
     * Test getting an invalid stored value and expecting an exception.
     */
    public function testGetInvalidStoredValueThrowsException(): void
    {
        $this->expectException(BadRequestException::class);
        $this->method->set('invalidStoredKey', new \stdClass());
        $this->method->get('invalidStoredKey');
    }
}
