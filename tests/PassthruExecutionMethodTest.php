<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

class PassthruExecutionMethodTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    // Root directory for the virtual filepassthru
    private $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('/var/www/html');
    }

    public function testImplementsExecutionMethod(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(ExecutionMethod::class, $passthruExecutionMethod);
    }

    public function testExtendsBlindExecutionMethod(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  

        // Expect the new instance to implement the interface ExecutionMethod
        $this->assertInstanceOf(BlindExecutionMethod::class, $passthruExecutionMethod);
    }

    public function testExecutionMethodCallsPassthru(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  
        $cmd = 'whoami';
        $output = 'www-data';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn(hex2bin('72616e646f6d5f746d705f66696c656e616d65'));
        
        // Mock the built-in `passthru()` function
        $passthru = $this->getFunctionMock(__NAMESPACE__, "passthru");
        $passthru->expects($this->once())->with('whoami > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
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
        $result = $passthruExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecutionMethodCallsPassthruAndReturnsResult(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  
        $cmd = 'pwd';
        $output = '/var/www/html';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn('random_tmp_filename');
        
        // Mock the built-in `passthru()` function
        $passthru = $this->getFunctionMock(__NAMESPACE__, "passthru");
        $passthru->expects($this->once())->with('pwd > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
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
        $result = $passthruExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecuteCallsPassthruAndReturnsFullCommandOutput(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  
        $cmd = 'ls -l';
        $output = "total 3\n";
        $output .= "drwxr-xr-x 2 www-data www-data  40 May 25 17:51 static\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 index.php\n";
        $output .= "-rw-r--r-- 1 www-data www-data  86 May 25 19:51 shell.php";
        
        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn(hex2bin('72616e646f6d5f746d705f66696c656e616d65'));
        
        // Mock the built-in `passthru()` function
        $passthru = $this->getFunctionMock(__NAMESPACE__, "passthru");
        $passthru->expects($this->once())->with('ls -l > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
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
        $result = $passthruExecutionMethod->execute($cmd);
        $this->assertEquals($output, $result);
    }

    public function testExecuteDeletesTemporaryFile(): void
    {
        // Create an PassthruExecutionMethod instance
        $passthruExecutionMethod = new PassthruExecutionMethod();  
        $cmd = 'pwd';
        $output = '/var/www/html';

        // Mock the built-in `random_bytes()` function
        $random_bytes = $this->getFunctionMock(__NAMESPACE__, "random_bytes");
        $random_bytes->expects($this->once())->with(32)->willReturn('random_tmp_filename');
        
        // Mock the built-in `passthru()` function
        $passthru = $this->getFunctionMock(__NAMESPACE__, "passthru");
        $passthru->expects($this->once())->with('pwd > 72616e646f6d5f746d705f66696c656e616d65.txt 2>&1')->willReturnCallback(
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
        $passthruExecutionMethod->execute($cmd);

        // Verify the output file has been deleted from the file passthru
        $this->assertFalse(file_exists('72616e646f6d5f746d705f66696c656e616d65.txt'));
    }
}
?>
