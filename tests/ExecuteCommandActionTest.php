<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class ExecuteCommandActionTest extends TestCase
{
    public function tearDown(): void
    {
        // Clean command history
        $instance = HistoryService::getInstance();
        $instance->clearHistory();
    }

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

    public function testAddsCommandToHistory(): void
    {
        // Initialize variables
        $cmd = 'whoami';
        $output = 'www-data';

        // Configure SystemService to use a mock ExecutionMethod
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $executionMethod->expects($this->once())->method('execute')->with($cmd)->willReturn($output);
        SystemService::getInstance()->setExecutionMethod($executionMethod);

        // Run the command
        $action = new ExecuteCommandAction;
        $args = (object) [ 'cmd' => $cmd ];
        $action->run($args);

        // Verify that the command is added to the history
        $history = HistoryService::getInstance()->getHistory();
        $this->assertContains($cmd, $history);
    }
}
?>
