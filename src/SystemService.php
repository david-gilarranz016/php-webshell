<?php
namespace WebShell;

class SystemService
{
    private static $instance = null;

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
}
?>
