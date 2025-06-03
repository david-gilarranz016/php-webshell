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

    public function testUsesSuppliedKeyToDecryptRequestBody()
    {
        // Generate a 256 bit key (32 bytes) for usage with AES-256 and a 128 bit (16 byte) initialization vector
        $key = random_bytes(32);
        $iv = random_bytes(16);

        // Create and Encrypt a sample body (base64 encoded AES encrypted message)
        $body = 'This is an example';
        $encryptedBody = openssl_encrypt($body, 'aes-256-cbc', $key, 0, $iv);

        // Initialize the SecurityService
        $instance = SecurityService::getInstance();
        $instance->setKey($key);

        // Decrypt the body and verify the result
        $result = $instance->decrypt($encryptedBody, $iv);
        $this->assertEquals($body, $result);
    }

    public function testReturnsEmptyStringIfBodyCannotBeDecrypted()
    {
        // Generate a 256 bit key (32 bytes) for usage with AES-256 and a 128 bit (16 byte) initialization vector
        $key = random_bytes(32);
        $iv = random_bytes(16);

        // Create and Encrypt a sample body with a different key
        $body = 'This is an example';
        $encryptedBody = openssl_encrypt($body, 'aes-256-cbc', random_bytes(32), 0, $iv);

        // Initialize the SecurityService
        $instance = SecurityService::getInstance();
        $instance->setKey($key);

        // Decrypt the body and verify the result
        $result = $instance->decrypt($encryptedBody, $iv);
        $this->assertEmpty($result);
    }
}
?>
