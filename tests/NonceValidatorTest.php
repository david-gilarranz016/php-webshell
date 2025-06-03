<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class NonceValidatorTest extends TestCase
{
    public function testImplementsValidatorInterface(): void
    {
        // Get a validator instance
        $validator = new NonceValidator;

        // Expect it to be a validator
        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testValidatesNonceAgainstSecurityService(): void
    {
        // Generate a random nonce and initialize the sercurity service
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
        // Generate a random nonce and initialize the sercurity service
        $nonce = random_bytes(16);
        SecurityService::getInstance()->setNonce($nonce);

        // Create a non valid request and a validator instance
        $request = [ 'body' => [ 'nonce' => random_bytes(16) ] ];
        $validator = new NonceValidator;

        // Validate the request and expect it not to be valid
        $valid = $validator->validate($request);
        $this->assertFalse($valid);
    }
}
?>
