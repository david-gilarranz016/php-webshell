<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SingletonSubclass extends Singleton { }
class DifferentSingletonSubclass extends Singleton { }

class SingletonTest extends TestCase
{
    public function testSingletonObjectsCannotBeCreatedWithNew(): void
    {
        // Attempting to instantiate a Singleton should raise an error
        $this->expectException(\Error::class);
        new SingletonSubclass;
    }
    
    public function testSingletonObjectsHaveGetInstanceMethod(): void
    {
        $instance = SingletonSubclass::getInstance();
        $this->assertInstanceOf(SingletonSubclass::class, $instance);
    }

    public function testGetInstanceReturnsTheSameObject(): void
    {
        $instance1 = SingletonSubclass::getInstance();
        $instance2 = SingletonSubclass::getInstance();
        $this->assertSame($instance1, $instance2);
    }

    public function testGetInstanceReturnsInstanceOfTheCorrectSubclass(): void
    {
        $instance = DifferentSingletonSubclass::getInstance();
        $this->assertInstanceOf(DifferentSingletonSubclass::class, $instance);
    }

    public function testSingletonObjectsCannotBeCloned(): void
    {
        // Attempting to clone a Singleton should raise an error
        $this->expectException(\Error::class);

        $instance = SingletonSubclass::getInstance(); 
        clone $instance;

    }

    public function testSingletonObjectCannotBeRestoredFromString(): void
    {
        // A function call to __wakeup should not be allowed
        $this->expectExceptionMessage('Cannot unserialize a singleton');

        // Get an instance and serialize it
        $instance = SingletonSubclass::getInstance();
        $serializedInstace = serialize($instance);

        // Attempt to unserialize the instance. It should raise an error.
        unserialize($serializedInstace);
    }
}
?>
