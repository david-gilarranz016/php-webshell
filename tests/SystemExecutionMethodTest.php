<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SystemExecutionMethodTest extends TestCase {
    use \phpmock\phpunit\PHPMock;

    public function testImplementsExecutionMethod(): void {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $systemExecutionMethod);
    }

    public function testExecutionMethodCallsSystem(): void {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'whoami';
        $output = 'www-data';
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with($cmd)->willReturn($output);

        // Call the function
        $result = $systemExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsSystemAndRetursResult(): void {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'ls';
        $output = '/var/www/html';
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with($cmd)->willReturn($output);

        // Call the function
        $result = $systemExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }
}
?>
