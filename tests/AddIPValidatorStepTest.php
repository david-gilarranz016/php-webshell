<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class AddIPValidatorStepTest extends TestCase
{
    public function tearDown(): void
    {
        // Get the validators property
        $instance = SecurityService::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $validators = $reflectedInstance->getProperty('validators');
        $validators->setAccessible(true);

        // Reset its value
        $validators->setValue($instance, []);
    }

    public function testImplementsStepInterface(): void
    {
        // Get a step instance
        $step = new AddIPValidatorStep([]);

        // Verify it is a Step
        $this->assertInstanceOf(Step::class, $step);
    }

    public function testAddsAnIPValidatorToTheListOfSecurityServiceValidatorsUsingAnInvalidRequest(): void
    {
        // Initialize variables
        $whitelist = ['1.2.3.4', '5.6.7.8'];

        // Call the step
        $step = new AddIPValidatorStep($whitelist);
        $step->run();

        // Create a valid request with a non valid IP and expect it to be rejected
        $request = new Request('9.0.1.2', 'test', new \stdClass());
        $valid = SecurityService::getInstance()->validate($request);

        $this->assertFalse($valid);
    }

    public function testAddsAnIPValidatorToTheListOfSecurityServiceValidatorsUsingAValidRequest(): void
    {
        // Initialize variables
        $whitelist = ['1.2.3.4', '5.6.7.8'];

        // Call the step
        $step = new AddIPValidatorStep($whitelist);
        $step->run();

        // Create a valid request with a non valid IP and expect it to be rejected
        $request = new Request($whitelist[0], 'test', new \stdClass());
        $valid = SecurityService::getInstance()->validate($request);

        $this->assertTrue($valid);
    }
}
?>
