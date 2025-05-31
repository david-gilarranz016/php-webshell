<?php
namespace WebShell;

abstract class Singleton
{

    private static $instances = [];

    // Singleton instances cannot be instantiated using `new`, cloned or deserialized
    protected function __construct() { }
    protected function __clone() { }
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton');
    }

    public static function getInstance()
    {
        // If there is not an instance registered for the concrete subclass, register it
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        // Return the instance corresponding to the subclass
        return self::$instances[$cls];
    }
}
?>
