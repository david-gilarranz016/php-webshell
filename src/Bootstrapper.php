<?php
namespace WebShell;

class Bootstrapper
{
    private $steps;

    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    public function launch(): void
    {
        // Run all initialization steps
        foreach ($this->steps as $step) {
            $step->run();
        }
    }
}
?>
