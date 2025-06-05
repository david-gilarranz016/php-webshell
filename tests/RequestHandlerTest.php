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

        // Set a default request source IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        
        // Initialize $_POST to an empty request to avoid errors on tests that do not
        // provide a specific request
        $this->createRequest(['action' => null]);
    }

    public function tearDown(): void
    {
        // Clear all added actions
        $requestHandler = RequestHandler::getInstance();
        $reflectedHandler = new \ReflectionObject($requestHandler);
        $actions = $reflectedHandler->getProperty('actions');
        $actions->setAccessible(true);
        $actions->setValue($requestHandler, []);

        // Clear all added validators
        $securityService = SecurityService::getInstance();
        $reflectedService = new \ReflectionObject($securityService);
        $actions = $reflectedService->getProperty('validators');
        $actions->setAccessible(true);
        $actions->setValue($securityService, []);


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
        // Initialize variables
        $key = 'test';
        $args = (object) [ 'argument' => 'value' ];
        $actionOutput = 'Sample output';

        // Create a sample action and expect it to be called with the created arguments
        $action = $this->createMock(Action::class);
        $action->expects($this->once())->method('run')->with($args)->willReturn($actionOutput);

        // Create a test request
        $this->createRequest([ 'action' => $key, 'args' => $args ]);

        // Add a request handler for the specified action
        $instance = RequestHandler::getInstance();
        $instance->addAction($key, $action);

        // Call the handle() method and decrypt the response
        $response = json_decode($instance->handle());
        $iv = base64_decode($response->iv);
        $jsonBody = SecurityService::getInstance()->decrypt($response->body, $iv);
        $body = json_decode($jsonBody);

        // Expect the response's output to match the Action's output
        $this->assertEquals($actionOutput, $body->output);
    }

    public function testThatASuccessfulRequestReturnsStatusCode200(): void
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

        // Call the handle() method
        $instance->handle();

        // Expect the status code to be 200
        $this->assertEquals(200, http_response_code());
    }

    public function testThatIfTheRequestCannotBeDecryptedAnErrorIsReturned(): void
    {
        // Initialize variables
        $key = 'test';
        $args = (object) [ 'argument' => 'value' ];

        // Create a test request
        $this->createRequest([ 'action' => $key, 'args' => $args ]);

        // Encrypt the request using a different key
        $iv = random_bytes(16);
        $body = openssl_encrypt(json_encode($args), 'aes-256-cbc', random_bytes(32), 0, $iv);
        $_POST = [ 'body' => $body, 'iv' => base64_encode($iv) ];

        // Handle the request
        RequestHandler::getInstance()->handle();

        // Expect result status code to be 403
        $this->assertEquals(403, http_response_code());
    }
    
    public function testThatIfTheRequestCannotBeDecryptedTheResponseBodyIsEmpty(): void
    {
        // Initialize variables
        $key = 'test';
        $args = (object) [ 'argument' => 'value' ];

        // Create a test request
        $this->createRequest([ 'action' => $key, 'args' => $args ]);

        // Encrypt the request using a different key
        $iv = random_bytes(16);
        $body = openssl_encrypt(json_encode($args), 'aes-256-cbc', random_bytes(32), 0, $iv);
        $_POST = [ 'body' => $body, 'iv' => base64_encode($iv) ];

        // Handle the request
        $response = RequestHandler::getInstance()->handle();

        // Expect the response to be an empty string
        echo $response;
        $this->assertEmpty($response);
    }

    public function testItRejectsNonValidRequests(): void
    {
        // Initialize variables
        $key = 'test';
        $args = (object) [ 'argument' => 'value' ];

        // Add an IP validator for a non-existent IP address, so as to invalidate the request
        $validator = new IPValidator(['1.2.3.4']);
        SecurityService::getInstance()->addValidator($validator);
        
        // Create and handle the request
        $this->createRequest([ 'action' => $key, 'args' => $args ]);
        RequestHandler::getInstance()->handle();

        // Expect the status code to be 403
        $this->assertEquals(403, http_response_code());
    }

    public function testStatusCode404IsReturnedForNonExistentActions(): void
    {
        // Create a request for a non-existing actions
        $this->createRequest([ 'action' => 'non-existent', 'args' => new \stdClass() ]);

        // Call the handle() method
        RequestHandler::getInstance()->handle();

        // Expect the status code to be 404
        $this->assertEquals(404, http_response_code());
    }

    public function testThatIfAnExceptionOccursStatusCode500IsReturned(): void
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
