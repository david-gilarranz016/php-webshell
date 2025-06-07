<?php
namespace WebShell;

class SetupRequestHandlerStep implements Step
{
    private $actions;

    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function run(): void
    {
        // Get the requestHandler instance
        $requestHandler = RequestHandler::getInstance();

        // Add the actions to the handler
        foreach (array_keys($this->actions) as $key) {
            $requestHandler->addAction($key, $this->actions[$key]);
        }
    }
}
?>
