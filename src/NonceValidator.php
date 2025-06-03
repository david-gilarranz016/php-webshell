<?php
namespace WebShell;

class NonceValidator implements Validator
{
    public function validate(array $request): bool
    {
        // Check that the request contains a body, it is an array, it contains a nonce
        // and the nonce is the one stored in the SecurityService
        return array_key_exists('body', $request) &&
            is_array($request['body']) &&
            array_key_exists('nonce', $request['body']) &&
            SecurityService::getInstance()->getNonce() == $request['body']['nonce'];
    }
}
?>
