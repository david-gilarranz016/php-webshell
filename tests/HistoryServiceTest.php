<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class HistoryServiceTest extends TestCase
{
    public function testHistoryServiceIsSingleton(): void
    {
        // Get an HistoryService instance
        $instance = HistoryService::getInstance();

        // Assert that it is a subclass of Singleton
        $this->assertInstanceOf(Singleton::class, $instance);
    }
}
?>
