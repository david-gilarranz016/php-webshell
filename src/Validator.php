<?php
namespace WebShell;

interface Validator
{
    public function validate(Request $request): bool;
}
?>
