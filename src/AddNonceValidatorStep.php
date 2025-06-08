<?php
namespace WebShell;

class AddNonceValidatorStep implements Step
{
    private $nonce;

    public function __construct(string $nonce)
    {
        $this->nonce = $nonce;
    }

    public function run(): void
    {
        // Add a nonce validator to the SecurityService  
        $nonceValidator = new NonceValidator;
        SecurityService::getInstance()->addValidator($nonceValidator);

        // If not initialized, set the initial nonce value
        if (!isset($_SESSION['nonce'])) {
            SecurityService::getInstance()->setNonce($this->nonce);
        }
    }
}
?>
