<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class BackticksExecutionMethodTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public static function setUpBeforeClass(): void
    {
        // Declare the function mock for the `ini_get` function
        static::defineFunctionMock(__NAMESPACE__, 'ini_get');
    }

    public function testImplementsExecutionMethod(): void
    {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $backticksExecutionMethod);
    }

    public function testExecutionMethodCallsBackticks(): void
    {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        $cmd = 'whoami';
        $output = shell_exec('whoami');
        
        // Call the function
        $result = $backticksExecutionMethod->execute($cmd);

        // Compare the result
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsBackticksAndReturnsResult(): void
    {
        // Create an BackticksExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        $cmd = 'pwd';
        $output = shell_exec('pwd');
        
        // Call the function
        $result = $backticksExecutionMethod->execute($cmd);

        // Compare the result
        $this->assertEquals($output, $result);
    }

    public function testIsAvailableReturnsFalseIfTheShellExecFunctionIsBlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('shell_exec')->willReturn(false);

        // Exepct isAvailable() to return false
        $this->assertFalse($backticksExecutionMethod->isAvailable());
    }

    public function testIsAvailableReturnsTrueIfTheShellExecFunctionIsNotlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('shell_exec')->willReturn(true);

        // Exepct isAvailable() to return false
        $this->assertTrue($backticksExecutionMethod->isAvailable());
    }

    public function testIsAvailableReturnsFalseIfSafeModeIsOn(): void
    {
        // Create an SystemExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        
        // Mock the built-in `ini_get()` function
        $ini_get = $this->getFunctionMock(__NAMESPACE__, "ini_get");
        $ini_get->expects($this->once())->with('safe_mode')->willReturn(true);

        // Exepct isAvailable() to return false
        $this->assertFalse($backticksExecutionMethod->isAvailable());
    }

    public function testIsAvailableReturnsTrueIfSafeModeIsOff(): void
    {
        // Create an SystemExecutionMethod instance
        $backticksExecutionMethod = new BackticksExecutionMethod();  
        
        // Mock the built-in `ini_get()` function
        $ini_get = $this->getFunctionMock(__NAMESPACE__, "ini_get");
        $ini_get->expects($this->once())->with('safe_mode')->willReturn(false);

        // Exepct isAvailable() to return false
        $this->assertTrue($backticksExecutionMethod->isAvailable());
    }
}
?>
