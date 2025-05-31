<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class ExecExecutionMethodTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testImplementsExecutionMethod(): void
    {
        // Create an SystemExecutionMethod instance
        $execExecutionMethod = new ExecExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $execExecutionMethod);
    }

    public function testExecutionMethodCallsShellExec(): void
    {
        // Create an SystemExecutionMethod instance
        $execExecutionMethod = new ExecExecutionMethod();  
        $cmd = 'whoami';
        $output = 'www-data';
        
        // Mock the built-in `exec()` function
        $exec = $this->getFunctionMock(__NAMESPACE__, "exec");
        $exec->expects($this->once())->with($cmd)->willReturnCallback(
            function ($command, &$output, &$return_var) {
                $this->assertEquals('whoami', $command);
                $output = ['www-data'];
                $return_var = 1;
            }
        );

        // Call the function
        $result = $execExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsShellExecAndReturnsResult(): void
    {
        // Create an SystemExecutionMethod instance
        $execExecutionMethod = new ExecExecutionMethod();  
        $cmd = 'ls -l';
        $output = "total 3\n";
        $output .= "drwxr-xr-x 2 www-data www-data  40 May 25 17:51 static\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 index.php\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php";
        
        // Mock the built-in `exec()` function
        $exec = $this->getFunctionMock(__NAMESPACE__, "exec");
        $exec->expects($this->once())->with($cmd)->willReturnCallback(
            function ($command, &$output, &$return_var) {
                $this->assertEquals('ls -l', $command);
                $output = [
                    "total 3",
                    "drwxr-xr-x 2 www-data www-data  40 May 25 17:51 static",
                    "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 index.php",
                    "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php"
                ];
                $return_var = 1;
            }
        );

        // Call the function
        $result = $execExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }
}
?>
