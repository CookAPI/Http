<?php

declare(strict_types=1);

use Cook\Component\Http\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Parameter class.
 */
final class ParameterTest extends TestCase
{
    private Parameter $parameter;

    protected function setUp(): void
    {
        $this->parameter = new Parameter(['key1' => 'value1', 'key2' => 42]);
    }

    /**
     * Test retrieving an existing parameter.
     */
    public function testGetExistingParameter(): void
    {
        $this->assertSame('value1', $this->parameter->get('key1'));
        $this->assertSame(42, $this->parameter->get('key2'));
    }

    /**
     * Test retrieving a non-existent parameter with a default value.
     */
    public function testGetNonExistentParameter(): void
    {
        $this->assertSame('default', $this->parameter->get('nonExistentKey', 'default'));
        $this->assertNull($this->parameter->get('anotherMissingKey'));
    }

    /**
     * Test setting a new parameter.
     */
    public function testSetParameter(): void
    {
        $this->parameter->set('newKey', 'newValue');
        $this->assertSame('newValue', $this->parameter->get('newKey'));
    }

    /**
     * Test removing a parameter.
     */
    public function testRemoveParameter(): void
    {
        $this->parameter->set('toRemove', 'removeMe');
        $this->assertSame('removeMe', $this->parameter->get('toRemove'));

        $this->parameter->remove('toRemove');
        $this->assertNull($this->parameter->get('toRemove'));
    }
}
