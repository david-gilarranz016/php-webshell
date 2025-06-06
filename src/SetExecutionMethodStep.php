<?php
namespace WebShell;

class SetExecutionMethodStep implements Step
{
    private $executionMethod;

    public function __construct(ExecutionMethod $executionMethod)
    {
        $this->executionMethod = $executionMethod;
    }


    public function run(): void
    {
        // Configure the SystemService to use the selected ExecutionMethod
        SystemService::getInstance()->setExecutionMethod($this->executionMethod);
    }
}
?>
