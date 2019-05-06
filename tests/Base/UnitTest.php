<?php


namespace Nicu\Tests\Base;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

abstract class UnitTest extends TestCase
{
    /**
     * Sets a protected property on a given object via reflection
     *
     * @param $object - instance in which protected value is being modified
     * @param $property - property on instance being modified
     * @param $value - new value of the property being modified
     *
     * @return bool
     */
    public function setProtectedProperty($object, $property, $value)
    {
        try {
            $reflection = new ReflectionClass($object);
            $reflection_property = $reflection->getProperty($property);
            $reflection_property->setAccessible(true);
            $reflection_property->setValue($object, $value);
            return true;
        } catch (ReflectionException $e) {
            return false;
        }
    }
}
