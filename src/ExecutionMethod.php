<?php
namespace WebShell;

interface ExecutionMethod
{
    public function execute(string $cmd): string;
    public function isAvailable(): bool;
}
?>
