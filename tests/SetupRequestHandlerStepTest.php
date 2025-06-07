<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SetupRequestHandlerStepTest extends TestCase
{
    public function tearDown(): void
    {
        // Clear all added actions
        $requestHandler = RequestHandler::getInstance();
        $reflectedHandler = new \ReflectionObject($requestHandler);
        $actions = $reflectedHandler->getProperty('actions');
        $actions->setAccessible(true);
        $actions->setValue($requestHandler, []);
    }

    public function testImplementsTheStepInterface(): void
    {
        // Create a step instance
        $step = new SetupRequestHandlerStep([]);

        // Expect it to implement the Step interface
        $this->assertInstanceOf(Step::class, $step);
    }

    public function testAddsTheActionsToTheRequestHandler(): void
    {
        // Create a mock action
        $key = 'test';
        $action = $this->createMock(Action::class);
        $actions = [ $key => $action ];

        // Create and run a step instance
        $step = new SetupRequestHandlerStep($actions);
        $step->run();

        // Expect the action to be added to the request handler
        $requestHandler = RequestHandler::getInstance();
        $reflectedHandler = new \ReflectionObject($requestHandler);
        $configuredActions = $reflectedHandler->getProperty('actions');
        $configuredActions->setAccessible(true);

        $this->assertEquals($actions, $configuredActions->getValue($requestHandler));
    }
}
?>
