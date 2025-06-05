<?php
namespace WebShell;

class IPValidator implements Validator
{
    private $ipWhiteList = [];

    public function __construct(array $ipWhiteList)
    {
        // Initialize the whitelist
        $this->ipWhiteList = $ipWhiteList;
    }

    public function validate(Request $request): bool
    {
        // Return true if the source address of the request is in the whitelist
        return in_array($request->getSource(), $this->ipWhiteList, true);
    }
}
?>
