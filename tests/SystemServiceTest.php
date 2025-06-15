<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SystemServiceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    // After each test is run, reset the current working directory to its original value
    public function tearDown(): void
    {
        unset($_SESSION['cwd']);
    }

    public function testIsSingleton(): void
    {
        // Get two instances from the class
        $instance = SystemService::getInstance();

        // Assert that it extends the Singleton class
        $this->assertInstanceOf(Singleton::class, $instance);
    } 

    public function testUsesExecutionMethodToRunSystemCommands(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Configure the mocked execution method to receive the command and return a canned output
        $cmd = 'whoami';
        $expectedOutput = 'www-data';
        $executionMethod->expects($this->once())->method('execute')->with($cmd)->willReturn($expectedOutput);

        // Assert that the SystemService instance is using the Mock object to run system calls
        $output = $instance->execute($cmd);
        $this->assertEquals($expectedOutput, $output);
    }

    public function testUsesExecutionMethodToInitializeCurrentDir(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Configure the mocked execution method to receive the command and return a canned output
        $cmd = 'pwd';
        $expectedDir = '/var/www/html';
        $executionMethod->expects($this->once())->method('execute')->with($cmd)->willReturn($expectedDir);

        // Assert that calling the getCurrentDir method uses the execution method if no previous directory is set
        $dir = $instance->getCurrentDir();
        $this->assertEquals($expectedDir, $dir);
    }

    public function testUpdatesCurrentDirectoryIfAcdComandIsReceived(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Set up variables and mock calls to is_dir
        $cmd = 'cd /home/web-admin';
        $expectedDir = '/home/web-admin';
        $is_dir = $this->getFunctionMock(__NAMESPACE__, "is_dir");
        $is_dir->expects($this->once())->with($expectedDir)->willReturn(true);

        // Run a command that modifies the current directory and assert that the new directory is stored
        $instance->execute($cmd);

        $this->assertEquals($expectedDir, $_SESSION['cwd']);
    }

    public function testDoesNotUpdateCurrentDirIfSpecifiedDirDoesNotExist(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Change to an "existing" directory
        $cmd = 'cd /home/web-admin';
        $expectedDir = '/home/web-admin';
        $is_dir = $this->getFunctionMock(__NAMESPACE__, "is_dir");
        $is_dir->expects($this->any())->willReturnOnConsecutiveCalls(true, false);
        $instance->execute($cmd);

        // Attempt to change to a non existing directory
        $instance->execute('cd /non/existent');

        // Expect directory to stay at the last successful change
        $this->assertEquals($expectedDir, $_SESSION['cwd']);
    }

    public function testKeepsBaseDirIfPathIsChildFolder(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Mock is_dir to return all directories exist
        $is_dir = $this->getFunctionMock(__NAMESPACE__, "is_dir");
        $is_dir->expects($this->any())->willReturnOnConsecutiveCalls(true, true);

        // Change to /home and then to a subdirectory
        $instance->execute('cd /home');
        $instance->execute('cd web-admin');

        // Expect directory to be the concatenation
        $this->assertEquals('/home/web-admin', $_SESSION['cwd']);
    }

    public function testAppendscdCommandToEmulateInteractiveShell(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Change to an "existing" directory
        $cmd = 'cd /home/web-admin';
        $cwd = '/home/web-admin';
        $is_dir = $this->getFunctionMock(__NAMESPACE__, "is_dir");
        $is_dir->expects($this->any())->willReturn(true);
        $instance->execute($cmd);

        // Configure the mocked execution method to receive the command and return a canned output
        $cmd = 'whoami';
        $expectedOutput = 'www-data';
        $executionMethod->expects($this->once())->method('execute')->with("cd '$cwd' && $cmd")->willReturn($expectedOutput);

        // Assert that the SystemService instance is using the Mock object to run system calls
        $output = $instance->execute($cmd);
        $this->assertEquals($expectedOutput, $output);
    }

    public function testExecutingAcdCommandReturnsTheNewDirectoryIfSuccessful(): void
    {
        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $instance->setExecutionMethod($executionMethod);

        // Change to an "existing" directory
        $dir = '/home/web-admin';
        $cmd = "cd $dir";
        $is_dir = $this->getFunctionMock(__NAMESPACE__, "is_dir");
        $is_dir->expects($this->any())->willReturn(true);
        $output = $instance->execute($cmd);

        // Expect the output to be the cwd
        $this->assertEquals($dir, $output);
    }

    public function testGetCurrentDirUsesSessionStorageToLoadCurrentDir(): void
    {
        // Set the directory in the session storage 
        $_SESSION['cwd'] = '/etc/apache2';

        // Get an instance and request the current directory
        $instance = SystemService::getInstance();
        $dir = $instance->getCurrentDir();

        // Expect the directory to equal the one in the session
        $this->assertEquals($_SESSION['cwd'], $dir);
    }

    public function testReturnsPWDResultIfNoCWDIsSet(): void
    {
        // Initialize variables
        $cwd = '/var/www/html';

        // Get an instance and set the ExecutionMethod
        $instance = SystemService::getInstance();
        $executionMethod = $this->createMock(ExecutionMethod::class);
        $executionMethod->expects($this->once())->method('execute')->with('pwd')->willReturn($cwd . "\n");
        $instance->setExecutionMethod($executionMethod);

        // Request the current directory and expect the output to be $cwd
        $output = $instance->getCurrentDir();
        $this->assertEquals($cwd, $output);
    }
}
?>
