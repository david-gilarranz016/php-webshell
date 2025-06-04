<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function setUp(): void
    {
        // Initialize Security service
        $key = random_bytes(32);
        SecurityService::getInstance()->setKey($key);

        // Initialize $_POST to an empty request to avoid errors on tests that do not
        // provide a specific request
        $this->createRequest(['action' => null]);
    }

    public function tearDown(): void
    {
        // Clear all added actions
        $instance = RequestHandler::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $actions = $reflectedInstance->getProperty('actions');
        $actions->setAccessible(true);
        $actions->setValue($instance, []);

        // Reset modified global variables
        $_POST = [];
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

    public function testRedirectsRequestToAppropriateAction(): void
    {
        // Initialize variables
        $key = 'test';
        $args = (object) [ 'argument' => 'value' ];

        // Create a sample action and expect it to be called with the created arguments
        $action = $this->createMock(Action::class);
        $action->expects($this->once())->method('run')->with($args)->willReturn('');

        // Create a test request
        $this->createRequest([ 'action' => $key, 'args' => $args ]);

        // Add a request handler for the specified action
        $instance = RequestHandler::getInstance();
        $instance->addAction($key, $action);

        // Call the handle() method and expect the mock action to have been called
        $instance->handle();
    }

    public function testItReturnsTheOutputProvidedByTheAction(): void
    {
        $this->markTestIncomplete();
    }

    public function testThatIfTheRequestCannotBeDecryptedAnErrorIsReturned(): void
    {
        $this->markTestIncomplete();
    }

    public function testItRejectsNonValidRequests(): void
    {
        $this->markTestIncomplete();
    }

    public function testErrorMessageIsReturnedForNonExistentActions(): void
    {
        $this->markTestIncomplete();
    }

    private function createRequest(array $body): void
    {
        // Set the POST 'body' and 'iv' parameters to the result of json_encoding and 
        // encrypting the supplied array
        $_POST = SecurityService::getInstance()->encrypt(json_encode($body));
    }
}
?>
