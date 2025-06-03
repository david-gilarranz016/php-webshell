<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SecurityServiceTest extends TestCase
{
    public function tearDown(): void
    {
        // After the tests are complete, delete any added validators
        $instance = SecurityService::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $validators = $reflectedInstance->getProperty('validators');
        $validators->setAccessible(true);
        $validators->setValue($instance, []);
    }

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

    public function testEncryptsBodyAndReturnsIv(): void
    {
        // Generate a 256 bit key and a sample response
        $key = random_bytes(32);
        $response = 'This is an example';

        // Initialize the SecurityService
        $instance = SecurityService::getInstance();
        $instance->setKey($key);

        // Encrypt the response
        [
            'body' => $body,
            'iv' => $iv
        ] = $instance->encrypt($response);

        // Expect the response to be decryptable
        $decryptedResponse = openssl_decrypt($body, 'aes-256-cbc', $key, 0, $iv);
        $this->assertEquals($response, $decryptedResponse);
    }

    public function testStoresAndReturnsNonce(): void
    {
        // Create a random nonce
        $nonce = random_bytes(16);

        // Initialize the SecurityService
        $instance = SecurityService::getInstance();
        $instance->setNonce($nonce);

        // Expect nonce to be stored
        $this->assertEquals($nonce, $instance->getNonce());
    }

    public function testReturnsTrueIfNoValidatorsAreUsedWhenValidatingRequests(): void
    {
        // Validate a sample request without configuring validators 
        $valid = SecurityService::getInstance()->validate([]);

        // Expect the request to be valid
        $this->assertTrue($valid);
    }

    public function testUsesSuppliedValidatorsToValidateRequest(): void
    {
        // Create two fake validators that result in a valid veredict
        $request = [ 'headers' => [], 'body' => [] ];
        $firstValidator = $this->createMock(Validator::class);
        $firstValidator->expects($this->once())->method('validate')->with($request)->willReturn(true);
        $secondValidator = $this->createMock(Validator::class);
        $secondValidator->expects($this->once())->method('validate')->with($request)->willReturn(true);

        // Add the validators to the SecurityService
        $instance = SecurityService::getInstance();
        $instance->addValidator($firstValidator);
        $instance->addValidator($secondValidator);

        // Validate a sample request and expect it to be valid
        $valid = $instance->validate($request);
        $this->assertTrue($valid);
    }

    public function testUsesSuppliedValidatorsToRejectNonValidRequest(): void
    {
        // Create two fake validators that result in a false veredict
        $request = [ 'headers' => [], 'body' => [] ];
        $firstValidator = $this->createMock(Validator::class);
        $firstValidator->expects($this->once())->method('validate')->with($request)->willReturn(true);
        $secondValidator = $this->createMock(Validator::class);
        $secondValidator->expects($this->once())->method('validate')->with($request)->willReturn(false);

        // Add the validators to the SecurityService
        $instance = SecurityService::getInstance();
        $instance->addValidator($firstValidator);
        $instance->addValidator($secondValidator);

        // Validate a sample request and expect it to be valid
        $valid = $instance->validate($request);
        $this->assertFalse($valid);
    }
}
?>
