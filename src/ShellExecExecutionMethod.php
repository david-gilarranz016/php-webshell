<?php
namespace WebShell;

class ShellExecExecutionMethod implements ExecutionMethod
{
    public function execute($cmd)
    {
        return shell_exec($cmd);
    }
}
?>
