<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SetExecutionMethodStepTest extends TestCase
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

    public function testImplementsTheStepInterface(): void
    {
        // Get a class instance
        $step = new SetExecutionMethodStep(new SystemExecutionMethod);

        // Verify it implements the expected interface
        $this->assertInstanceOf(Step::class, $step);
    }

    public function testSetsTheSystemServiceExecutionMethod(): void
    {
        // Initialize variables
        $cmd = 'whoami';
        $output = 'www-data';

        // Create a mock execution method and expect it to be called
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $executionMethod->expects($this->once())->method('execute')->with($cmd)->willReturn($output);

        // Create and run a SetExecutionMethod step
        $step = new SetExecutionMethodStep($executionMethod);
        $step->run();

        // Use the SystemService to execute a command and expect the result to be $output
        $result = SystemService::getInstance()->execute($cmd);
        $this->assertEquals($output, $result);
    }
}
?>
