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
}
?>
