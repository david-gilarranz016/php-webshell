<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class AddNonceValidatorStepTest extends TestCase
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
        $step = new AddNonceValidatorStep(random_bytes(16));

        // Verify it is a Step
        $this->assertInstanceOf(Step::class, $step);
    }

    public function testAddsANonceValidatorToTheListOfSecurityServiceValidators(): void
    {
        // Call the step
        $step = new AddNonceValidatorStep(random_bytes(16));
        $step->run();

        // Get the list of validators and expect it to contain the Nonce validator
        $validators = $this->getValidators();
        $this->assertInstanceOf(NonceValidator::class, $validators[0]);
    }

    public function testInicializesSecurityServiceNonce(): void
    {
        // Initialize variables
        $nonce = random_bytes(16);

        // Call the step
        $step = new AddNonceValidatorStep($nonce);
        $step->run();

        // Expect the Nonce to have been initialzied
        $this->assertEquals($nonce, SecurityService::getInstance()->getNonce());
    }

    private function getValidators(): array
    {
        // Get the reflected property and make it accessible
        $instance = SecurityService::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $validators = $reflectedInstance->getProperty('validators');
        $validators->setAccessible(true);

        // Return its value
        return $validators->getValue($instance);
    }
}
?>
