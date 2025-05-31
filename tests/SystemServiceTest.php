<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class SystemServiceTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    // After each test is run, reset the current working directory to its original value
    public function tearDown(): void
    {
        $instance = SystemService::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $currentDir = $reflectedInstance->getProperty('currentDir');
        $currentDir->setAccessible(true);
        $currentDir->setValue($instance, '');
    }

    public function testIsSingleton(): void
    {
        // Get two instances from the class
        $instance1 = SystemService::getInstance();
        $instance2 = SystemService::getInstance();

        // Assert they are the same object
        $this->assertEquals($instance1, $instance2);
    } 

    public function testCannotBeRestoredFromString(): void
    {
        // A function call to __wakeup should not be allowed
        $this->expectExceptionMessage('Cannot unserialize a singleton');

        // Get an instance and serialize it
        $instance = SystemService::getInstance();
        $serializedInstace = serialize($instance);

        // Attempt to unserialize the instance. It should raise an error.
        unserialize($serializedInstace);
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
        $dir = $instance->getCurrentDir();

        $this->assertEquals($expectedDir, $dir);
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
        $dir = $instance->getCurrentDir();
        $this->assertEquals($expectedDir, $dir);
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
}
?>
