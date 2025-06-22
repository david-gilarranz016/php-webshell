<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class ExecuteCommandActionTest extends TestCase
{
    public function testImplementsActionInterface(): void
    {
        // Get an action instance
        $action = new ExecuteCommandAction;

        // Assert it implements the Action interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testUsesSystemServiceToRunCommands(): void
    {
        // Initialize variables
        $cmd = 'whoami';
        $output = 'www-data';

        // Configure SystemService to use a mock ExecutionMethod
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $executionMethod->expects($this->once())->method('execute')->with($cmd)->willReturn($output);
        SystemService::getInstance()->setExecutionMethod($executionMethod);
        
        // Create test payload and run the action
        $args = (object) [ 'cmd' => $cmd ];
        $action = new ExecuteCommandAction;
        $result = $action->run($args);

        // Expect result to be successful
        $this->assertEquals($output, $result);
    }
}
?>
