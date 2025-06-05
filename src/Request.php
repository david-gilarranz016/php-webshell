<?php
namespace WebShell;

class Request
{
    private $source;
    private $action;
    private $args;
    private $nonce;

    public function __construct(
        ?string $source = null,
        ?string $action = null,
        ?object $args = null,
        ?string $nonce = null
    )
    {
        $this->source = $source;
        $this->action = $action;
        $this->args = $args;
        $this->nonce = $nonce;
    }

    public function isValid(): bool
    {
        return !(is_null($this->action) || is_null($this->args));
    }

    public function getSource(): ?string
    {
        return $this->source;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getArgs(): ?object
    {
        return $this->args;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }
}
?>
