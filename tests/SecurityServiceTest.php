<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SecurityServiceTest extends TestCase
{
    public function testIsSingleton(): void
    {
        // Get a SecurityService instance
        $instance = SecurityService::getInstance();

        // Expect the intance to be a Singleton
        $this->assertInstanceOf(Singleton::class, $instance);
    }
}
?>
