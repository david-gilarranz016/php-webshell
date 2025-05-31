<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SystemServiceTest extends TestCase
{
    public function testIsSingleton(): void
    {
        // Get two instances from the class
        $instance1 = SystemService::getInstance();
        $instance2 = SystemService::getInstance();

        // Assert they are the same object
        $this->assertEquals($instance1, $instance2);
    } 

    public function testCannotBeRestoredFromString(): void
    {
        // A function call to __wakeup should not be allowed
        $this->expectExceptionMessage('Cannot unserialize a singleton');

        // Get an instance and serialize it
        $instance = SystemService::getInstance();
        $serializedInstace = serialize($instance);

        // Attempt to unserialize the instance. It should raise an error.
        unserialize($serializedInstace);
    }
}
?>
