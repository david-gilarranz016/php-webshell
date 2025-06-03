<?php
namespace WebShell;

class SecurityService extends Singleton
{
    private $key;

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function encrypt(string $body): array
    {
        // Generate an initialization vector for the encryption process
        $iv = random_bytes(16);
        $body = openssl_encrypt($body, 'aes-256-cbc', $this->key, 0, $iv);

        // Return both the encrypted body and the initialization vector
        return [
            'body' => $body,
            'iv' => $iv
        ];
    }

    public function decrypt(string $body, string $iv): string
    {
        return openssl_decrypt($body, 'aes-256-cbc', $this->key, 0, $iv); 
    }

}
?>
