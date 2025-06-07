<?php
namespace WebShell;

class SetupEncryptionStep implements Step
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function run(): void
    {
        // Configure the SecurityService 
        SecurityService::getInstance()->setKey($this->key);
    }
}
