<?php
namespace WebShell;

class AddIPValidatorStep implements Step
{
    private $whitelist;

    public function __construct(array $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    public function run(): void
    {
        // Create a new IP validator with the supplied whitelist
        $ipValidator = new IPValidator($this->whitelist);

        // Add the validator to the SecurityService
        SecurityService::getInstance()->addValidator($ipValidator);
    }
}
?>
