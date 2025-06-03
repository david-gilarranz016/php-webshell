<?php
namespace WebShell;

interface Validator
{
    public function validate(array $request): bool;
}
?>
