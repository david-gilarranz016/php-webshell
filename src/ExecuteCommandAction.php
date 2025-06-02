<?php
namespace WebShell;

class ExecuteCommandAction implements Action
{
    public function run(array $args): string
    {
        // Extract the command from the arguments
        $cmd = $args['cmd'];

        // Run the command and add it to the command history
        $output = SystemService::getInstance()->execute($cmd);
        HistoryService::getInstance()->addCommand($cmd);

        // Return the command output
        return $output;
    }
}
?>
