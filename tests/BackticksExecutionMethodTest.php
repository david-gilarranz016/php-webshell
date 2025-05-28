<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class BackticksExecutionMethodTest extends TestCase {
    use \phpmock\phpunit\PHPMock;

    public function testImplementsExecutionMethod(): void {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $backticksExecutionMethod);
    }

    public function testExecutionMethodCallsBackticks(): void {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        $cmd = 'whoami';
        $output = shell_exec('whoami');
        
        // Call the function
        $result = $backticksExecutionMethod->execute($cmd);

        // Compare the result
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsBackticksAndReturnsResult(): void {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        $cmd = 'pwd';
        $output = shell_exec('pwd');
        
        // Call the function
        $result = $backticksExecutionMethod->execute($cmd);

        // Compare the result
        $this->assertEquals($output, $result);
    }
}
?>
