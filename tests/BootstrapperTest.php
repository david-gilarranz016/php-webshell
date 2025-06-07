<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class BootstrapperTest extends TestCase
{
    public function testRunsTheSuppliedSteps(): void
    {
        // Create 5 steps
        $steps = [];
        for ($i = 0; $i < 5; $i++) {
            // Create a step and expect it to be run
            $step = $this->createMock(Step::class);
            $step->expects($this->once())->method('run');

            // Add the step to the list
            array_push($steps, $step);
        }

        // Create and run the Bootstrapper. All 5 steps should be run
        $bootstrapper = new Bootstrapper($steps);
        $bootstrapper->launch();
    }
}
?>
