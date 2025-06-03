<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function tearDown(): void
    {
        // Clear all added actions
        $instance = RequestHandler::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $actions = $reflectedInstance->getProperty('actions');
        $actions->setAccessible(true);
        $actions->setValue($instance, []);
    }

    public function testIsSingleton(): void
    {
        // Get a Request Handler instance
        $instance = RequestHandler::getInstance();

        // Expect it to be a Singleton
        $this->assertInstanceOf(Singleton::class, $instance);
    }

    public function testAddsJsonContentTypeHeader(): void
    {
        // Mock the headers function 
        $headers = $this->getFunctionMock(__NAMESPACE__, 'header');
        $headers->expects($this->once())->with('Content-Type: application/json');

        // Call the handle() method
        RequestHandler::getInstance()->handle();
    }
}
?>
