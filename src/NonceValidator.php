<?php
namespace WebShell;

class NonceValidator implements Validator
{
    public function validate(Request $request): bool
    {
        // Check that nonce is the one stored in the SecurityService
        $securityService = SecurityService::getInstance();
        $valid = $request->getNonce() == $securityService->getNonce();

        // If the nonce is valid, update the SecurityService to generate a new Nonce
        if ($valid) {
            $nonce = random_bytes(16);
            $securityService->setNonce($nonce);
        }

        // Return the validation result
        return $valid;
    }
}
?>
