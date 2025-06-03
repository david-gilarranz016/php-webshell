<?php
namespace WebShell;

class SecurityService extends Singleton
{
    private $key;
    private $nonce;
    private $validators = [];

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

    public function validate(array $request): bool
    {
        // Return `true` by default
        $valid = true;

        // Pass the request to all configured validators
        for ($i = 0; $i < count($this->validators) && $valid; $i++) {
            $valid &= $this->validators[$i]->validate($request);
        }

        // Return the result
        return $valid;
    }

    public function addValidator(Validator $validator): void
    {
        array_push($this->validators, $validator);
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }
}
?>
