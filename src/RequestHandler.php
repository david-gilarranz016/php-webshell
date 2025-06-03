<?php
namespace WebShell;

class RequestHandler extends Singleton
{
    private $actions = [];

    public function handle(): string
    {
        // Set the content-type header
        header('Content-Type: application/json');
        return '';
    }
}
?>
