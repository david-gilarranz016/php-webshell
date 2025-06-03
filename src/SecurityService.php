<?php
namespace WebShell;

class SecurityService extends Singleton
{
    private $key;

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function decrypt(string $body, string $iv): string
    {
        return openssl_decrypt($body, 'aes-256-cbc', $this->key, 0, $iv); 
    }
}
?>
