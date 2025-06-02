<?php
namespace WebShell;

interface Action
{
    public function run(array $args): string;
}
?>
