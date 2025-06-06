<?php
namespace WebShell;

class AddNonceValidatorStep implements Step
{
    public function run(): void
    {
        // Add a nonce validator to the SecurityService  
        $nonceValidator = new NonceValidator;
        SecurityService::getInstance()->addValidator($nonceValidator);
    }
}
?>
