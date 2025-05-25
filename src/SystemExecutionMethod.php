<?php
namespace WebShell;

class SystemExecutionMethod implements ExecutionMethod {
    public function execute($cmd) {
        return system($cmd);
    }
}
?>
