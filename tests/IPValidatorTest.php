<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class IPValidatorTest extends TestCase
{
    public function testImplementsValidatorInterface(): void
    {
        // Get a validator instance
        $validator = new IPValidator([]);

        // Assert it implements the expected interface
        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testReturnsTrueIfTheIPIsInTheWhiteList(): void
    {
        // Create a validator
        $ipWhitelist = [ '10.24.126.52', '129.12.42.164' ];
        $validator = new IPValidator($ipWhitelist);

        // Craft a fake request
        $request = new Request($ipWhitelist[1]);

        // Expect the request to be valid
        $valid = $validator->validate($request);
        $this->assertTrue($valid);
    }

    public function testReturnsFalseIfTheIPIsNotInTheWhiteList(): void
    {
        // Create a validator
        $ipWhitelist = [ '10.24.126.52', '129.12.42.164' ];
        $validator = new IPValidator($ipWhitelist);

        // Craft a fake request
        $request = new Request('213.41.56.252');

        // Expect the request to be valid
        $valid = $validator->validate($request);
        $this->assertFalse($valid);
    }
}
?>
