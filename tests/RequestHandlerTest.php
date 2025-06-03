<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    public function testIsSingleton(): void
    {
        // Get a Request Handler instance
        $instance = RequestHandler::getInstance();

        // Expect it to be a Singleton
        $this->assertInstanceOf(Singleton::class, $instance);
    }
}
?>
