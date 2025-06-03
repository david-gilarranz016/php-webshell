<?php
namespace WebShell;

class NonceValidator implements Validator
{
    public function validate(array $request): bool
    {
        // Check that the request contains a body, it is an array, it contains a nonce
        // and the nonce is the one stored in the SecurityService
        $instance = SecurityService::getInstance();
        $valid = array_key_exists('body', $request)          &&
                 is_array($request['body'])                  &&
                 array_key_exists('nonce', $request['body']) &&
                 $instance->getNonce() == $request['body']['nonce'];

        // If valid, update the nonce
        if ($valid) {
            $nonce = random_bytes(16);
            $instance->setNonce($nonce);
        }

        // Return the validation result
        return $valid;
    }
}
?>
