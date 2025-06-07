<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class ShellExecExecutionMethodTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testImplementsExecutionMethod(): void
    {
        // Create an SystemExecutionMethod instance
        $shellExecExecutionMethod = new ShellExecExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $shellExecExecutionMethod);
    }

    public function testExecutionMethodCallsShellExec(): void
    {
        // Create an SystemExecutionMethod instance
        $shellExecExecutionMethod = new ShellExecExecutionMethod();  
        $cmd = 'whoami';
        $output = 'www-data';
        
        // Mock the built-in `shell_exec()` function
        $shell_exec = $this->getFunctionMock(__NAMESPACE__, "shell_exec");
        $shell_exec->expects($this->once())->with($cmd)->willReturn($output);

        // Call the function
        $result = $shellExecExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsShellExecAndReturnsResult(): void
    {
        // Create an SystemExecutionMethod instance
        $shellExecExecutionMethod = new ShellExecExecutionMethod();  
        $cmd = 'pwd';
        $output = '/var/www/html';
        
        // Mock the built-in `shell_exec()` function
        $shell_exec = $this->getFunctionMock(__NAMESPACE__, "shell_exec");
        $shell_exec->expects($this->once())->with($cmd)->willReturn($output);

        // Call the function
        $result = $shellExecExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testIsAvailableReturnsFalseIfTheFunctionIsBlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $shellExecExecutionMethod = new ShellExecExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('shell_exec')->willReturn(false);

        // Exepct isAvailable() to return false
        $this->assertFalse($shellExecExecutionMethod->isAvailable());
    }

    public function testIsAvailableReturnsTrueIfTheFunctionIsNotlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $shellExecExecutionMethod = new ShellExecExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('shell_exec')->willReturn(true);

        // Exepct isAvailable() to return false
        $this->assertTrue($shellExecExecutionMethod->isAvailable());
    }
}
?>
