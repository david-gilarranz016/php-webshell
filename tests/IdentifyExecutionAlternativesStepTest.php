<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class IdentifyExecutionAlternativesStepTest extends TestCase
{
    public function tearDown(): void
    {
        // Reset the SystemService to it's original state
        $systemService = SystemService::getInstance();
        $reflectedInstance = new \ReflectionObject($systemService);
        $executionMethod = $reflectedInstance->getProperty('executionMethod');
        $executionMethod->setAccessible(true);
        $executionMethod->setValue($systemService, null);
    }

    public function testImplementsStepInterface(): void
    {
        // Get a step instance
        $step = new IdentifyExecutionAlternativesStep([]);

        // Expect the instance to be a Step
        $this->assertInstanceOf(Step::class, $step);
    }

    public function testItAddsTheFirstValidExecutionAlternativeToSysteService(): void
    {
        // Create a list of execution methods
        $expectedOutput = 'Sample output';
        $executionMethods = $this->createExecutionMethods([false, false, true, true], ['1', '2', $expectedOutput, '3']);

        // Create and run the Step
        $step = new IdentifyExecutionAlternativesStep($executionMethods);
        $step->run();

        // Run a command and expect the third execution method to be used
        $output = SystemService::getInstance()->execute('test');
        $this->assertEquals($expectedOutput, $output);
    }

    private function createExecutionMethods(array $validMethods, array $outputs): array
    {
        $executionMethods = [];

        for ($i = 0; $i < sizeof($validMethods) && $i < sizeof($outputs); $i++) {
            // Create a Mock execution method
            $executionMethod = $this->createMock(ExecutionMethod::class);
            $executionMethod->method('isAvailable')->willReturn($validMethods[$i]);
            $executionMethod->method('execute')->willReturn($outputs[$i]);

            // Add to the list
            array_push($executionMethods, $executionMethod);
        }

        return $executionMethods;
    }
}
?>
