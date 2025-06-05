<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCanHaveNullIp(): void
    {
        // Create empty request
        $request = new Request();

        // Expect source to be null
        $this->assertNull($request->getSource());
    }

    public function testCanHaveNullAction(): void
    {
        // Create empty request
        $request = new Request();

        // Expect action to be null
        $this->assertNull($request->getAction());
    }

    public function testCanHaveNullArgs(): void
    {
        // Create empty request
        $request = new Request();

        // Expect args to be null
        $this->assertNull($request->getArgs());
    }

    public function testCanHaveNullNonce(): void
    {
        // Create empty request
        $request = new Request();

        // Expect nonce to be null
        $this->assertNull($request->getNonce());
    }

    public function testCanBeInstantiatedWithSourceIP(): void
    {
        // Create a request object
        $source = '1.2.3.4';
        $request = new Request($source);

        // Expect action to be the supplied value
        $this->assertEquals($source, $request->getSource());
    }
    
    public function testCanBeInstantiatedWithTargetAction(): void
    {
        // Create a request object
        $action = 'test';
        $request = new Request('1.2.3.4', $action);

        // Expect action to be the supplied value
        $this->assertEquals($action, $request->getAction());
    }

    public function testCanBeInstantiatedWithTargetArgs(): void
    {
        // Create a request object
        $args = new \stdClass();
        $request = new Request('1.2.3.4', 'test', $args);

        // Expect args to be the supplied value
        $this->assertEquals($args, $request->getArgs());
    }

    public function testCanBeInstantiatedWithTargetNonce(): void
    {
        // Create a request object
        $nonce = random_bytes(16);
        $request = new Request('1.2.3.4', 'test', new \stdClass(), $nonce);

        // Expect nonce to be the supplied value
        $this->assertEquals($nonce, $request->getNonce());
    }

    public function testRequestWithNullActionIsNotValid(): void
    {
        // Create a request with a null action
        $request = new Request('1.2.3.4', null, new \stdClass(), random_bytes(16));

        // Expect the request not to be valid
        $this->assertFalse($request->isValid());
    }

    public function testRequestWithNullArgsIsNotValid(): void
    {
        // Create a request with a null action
        $request = new Request('1.2.3.4', 'test', null, random_bytes(16));

        // Expect the request not to be valid
        $this->assertFalse($request->isValid());
    }

    public function testRequestWithActionAndArgsIsValid(): void
    {
        // Create a request object
        $request = new Request('1.2.3.4', 'test', new \stdClass());

        // Expect it to be valid
        $this->assertTrue($request->isValid());
    }
}
?>
