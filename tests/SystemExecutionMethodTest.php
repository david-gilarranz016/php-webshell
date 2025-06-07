<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

class SystemExecutionMethodTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    // Root directory for the virtual filesystem
    private $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('/var/www/html');
    }

    public function testImplementsExecutionMethod(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $systemExecutionMethod);
    }

    public function testExecutionMethodCallsSystem(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'whoami';
        $output = 'www-data';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn(hex2bin('72616e646f6d5f746d705f66696c656e616d65'));
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with('whoami > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
            function (string $command) {
                // Write output into tmp file to mock the redirection
                $fd = fopen('72616e646f6d5f746d705f66696c656e616d65.txt', 'w');
                $txt = "www-data";
                fwrite($fd, $txt);
                fclose($fd);

                // Return last output line to mimick the original function
                return $txt;
            }
        );

        // Call the function
        $result = $systemExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsSystemAndReturnsResult(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'pwd';
        $output = '/var/www/html';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn('random_tmp_filename');
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with('pwd > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
            function (string $command) {
                // Write output into tmp file to mock the redirection
                $fd = fopen('72616e646f6d5f746d705f66696c656e616d65.txt', 'w');
                $txt = "/var/www/html";
                fwrite($fd, $txt);
                fclose($fd);

                // Return last output line to mimick the original function
                return $txt;
            }
        );

        // Call the function
        $result = $systemExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecuteCallsSystemAndReturnsFullCommandOutput(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'ls -l';
        $output = "total 3\n";
        $output .= "drwxr-xr-x 2 www-data www-data  40 May 25 17:51 static\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 index.php\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php";
        
        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn(hex2bin('72616e646f6d5f746d705f66696c656e616d65'));
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with('ls -l > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
            function (string $command) {
                // Write full output into tmp file to mock the redirection
                $fd = fopen('72616e646f6d5f746d705f66696c656e616d65.txt', 'w');
                $txt = "total 3\n";
                $txt .= "drwxr-xr-x 2 www-data www-data  40 May 25 17:51 static\n";
                $txt .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 index.php\n";
                $txt .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php";
                fwrite($fd, $txt);
                fclose($fd);

                // Return last output line to mimick the original function
                return "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php";
            }
        );

        // Call the function
        $result = $systemExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecuteDeletesTemporaryFile(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        $cmd = 'pwd';
        $output = '/var/www/html';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn('random_tmp_filename');
        
        // Mock the built-in `system()` function
        $system = $this->getFunctionMock(__NAMESPACE__, "system");
        $system->expects($this->once())->with('pwd > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
            function (string $command) {
                // Write output into tmp file to mock the redirection
                $fd = fopen('72616e646f6d5f746d705f66696c656e616d65.txt', 'w');
                $txt = "/var/www/html";
                fwrite($fd, $txt);
                fclose($fd);

                // Return last output line to mimick the original function
                return $txt;
            }
        );

        // Call the function
        $systemExecutionMethod->execute($cmd);

        // Verify the output file has been deleted from the file system
        $this->assertFalse(file_exists('72616e646f6d5f746d705f66696c656e616d65.txt'));
    }

    public function testIsAvailableReturnsFalseIfTheFunctionIsBlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('system')->willReturn(false);

        // Exepct isAvailable() to return false
        $this->assertFalse($systemExecutionMethod->isAvailable());
    }

    public function testIsAvailableReturnsTrueIfTheFunctionIsNotlocked(): void
    {
        // Create an SystemExecutionMethod instance
        $systemExecutionMethod = new SystemExecutionMethod();  
        
        // Mock the built-in `function_exists()` function
        $function_exists = $this->getFunctionMock(__NAMESPACE__, "function_exists");
        $function_exists->expects($this->once())->with('system')->willReturn(true);

        // Exepct isAvailable() to return false
        $this->assertTrue($systemExecutionMethod->isAvailable());
    }
}
?>
