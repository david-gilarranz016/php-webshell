<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class NonceValidatorTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    /**
     * Define the random_bytes function mock before running the class to fix Bug #68541
     *
     * @beforeClass
     */ 
    public function setUp(): void
    {
        $this->defineFunctionMock(__NAMESPACE__, 'random_bytes');
    }

    public function testImplementsValidatorInterface(): void
    {
        // Get a validator instance
        $validator = new NonceValidator;

        // Expect it to be a validator
        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testValidatesNonceAgainstSecurityService(): void
    {
        // Generate a random nonce and initialize the security service
        $nonce = random_bytes(16);
        SecurityService::getInstance()->setNonce($nonce);

        // Create a valid request and a validator instance
        $request = [ 'body' => [ 'nonce' => $nonce ] ];
        $validator = new NonceValidator;

        // Validate the request and expect it to be valid
        $valid = $validator->validate($request);
        $this->assertTrue($valid);
    }

    public function testValidatesNonceAgainstSecurityServiceAndRejectsNonValidRequests(): void
    {
        // Generate a random nonce and initialize the security service
        $nonce = random_bytes(16);
        SecurityService::getInstance()->setNonce($nonce);

        // Create a non valid request and a validator instance
        $request = [ 'body' => [ 'nonce' => random_bytes(16) ] ];
        $validator = new NonceValidator;

        // Validate the request and expect it not to be valid
        $valid = $validator->validate($request);
        $this->assertFalse($valid);
    }

    public function testUpdatesNonceIfRequestIsValid(): void
    {
        // Generate a random nonce and initialize the security service
        $nonce = '0x92847f7ed261cc4d7ec0dacb6310b1db';
        SecurityService::getInstance()->setNonce($nonce);

        // Mock the random_bytes function to return a canned result
        $newNonce = '0x335513fa6154be4225a22befb24cd852';
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(16)->willReturn($newNonce);

        // Create and validate a valid request
        $request = [ 'body' => [ 'nonce' => $nonce ] ];
        $validator = new NonceValidator;
        $validator->validate($request);

        // Expect the SecurityService's nonce to have been updated
        $this->assertEquals($newNonce, SecurityService::getInstance()->getNonce());
    }

    public function testDoesNotUpdateNonceIfRequestIsValid(): void
    {
        // Generate a random nonce and initialize the security service
        $nonce = random_bytes(16);
        SecurityService::getInstance()->setNonce($nonce);

        // Create and validate an invalid request
        $request = [ 'body' => [ 'nonce' => 'non-valid-nonce' ] ];
        $validator = new NonceValidator;
        $validator->validate($request);

        // Expect the SecurityService's nonce keep its original value
        $this->assertEquals($nonce, SecurityService::getInstance()->getNonce());
    }
}
?>
