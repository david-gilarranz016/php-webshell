<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class DeleteHistoryActionTest extends TestCase
{
    public function testImplementsActionInterface(): void
    {
        // Get an action instance
        $action = new DeleteHistoryAction;

        // Verify that it implements the Action interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testDeletsCommandHistory(): void
    {
        // Add some commands to the history
        $instance = HistoryService::getInstance();
        $instance->addCommand('id');
        $instance->addCommand('pwd');

        // Call the DeleteHistoryAction 
        $action = new DeleteHistoryAction;
        $action->run((object) []);

        // Expect the history to be empty
        $history = $instance->getHistory();
        $this->assertEmpty($history);
    }
}
?>
