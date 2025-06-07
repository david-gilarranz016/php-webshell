<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SetupEncryptionStepTest extends TestCase
{
    public function tearDown(): void
    {
        // Resets SecurityService
        $securityService = SecurityService::getInstance();
        $reflectedInstance = new \ReflectionObject($securityService);
        $key = $reflectedInstance->getProperty('key');
        $key->setAccessible(true);
        $key->setValue($securityService, '');
    }

    public function testImplementsStepInterface(): void
    {
        // Get a step instance
        try {
        $key = random_bytes(32);
        $step = new SetupEncryptionStep($key);

        // Expect it to be a Step
        $this->assertInstanceOf(Step::class, $step);

        } catch (\Error $e) {
            echo var_dump($e);
        }
    }

    public function testSetsTheSecurityServiceKey(): void
    {
        // Create a random key
        $key = random_bytes(32);

        // Run the step
        $step = new SetupEncryptionStep($key);
        $step->run();

        // Expect the key to have been set
        $securityService = SecurityService::getInstance();
        $reflectedInstance = new \ReflectionObject($securityService);
        $configuredKey = $reflectedInstance->getProperty('key');
        $configuredKey->setAccessible(true);

        $this->assertEquals($key, $configuredKey->getValue($securityService));
    }
}
?>
