<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class ShowHistoryActionTest extends TestCase
{
    public function tearDown(): void
    {
        // Revert any changes made to the command history
        HistoryService::getInstance()->clearHistory();
    }

    public function testImplementsActionInterface(): void
    {
        // Get an instance of the action
        $action = new ShowHistoryAction;

        // Verify the action implements the Action interface        
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testReturnsCommandHistory(): void
    {
        // Add a series of commands to the command history
        $instance = HistoryService::getInstance();
        $commands = [ 'id', 'pwd', 'ls -l /home', 'cd /home/web-admin' ];
        foreach ($commands as $cmd) {
            $instance->addCommand($cmd);
        }

        // Run a ShowHistoryAction
        $action = new ShowHistoryAction;
        $result = $action->run((object) []);

        // Assert that a list of commands separated by newline characters is returned
        $this->assertEquals(implode("\n", $commands), $result);
    }

    public function testCanSearchCommandsIfRequested(): void
    {
        // Add a series of commands to the command history
        $instance = HistoryService::getInstance();
        $commands = [ 'id', 'pwd', 'ls -l /home', 'cd /home/web-admin', 'ls -la' ];
        foreach ($commands as $cmd) {
            $instance->addCommand($cmd);
        }

        // Run a ShowHistoryAction that searches instances of the `ls` command
        $action = new ShowHistoryAction;
        $args = (object) ['search' => 'ls'];
        $result = $action->run($args);

        // Assert that only `ls` commands are returned
        $this->assertEquals("ls -l /home\nls -la", $result);
    }
}
?>
