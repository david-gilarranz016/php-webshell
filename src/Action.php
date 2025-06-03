<?php
namespace WebShell;

interface Action
{
    public function run(object $args): string;
}
?>
