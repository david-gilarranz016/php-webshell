<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class HistoryServiceTest extends TestCase
{
    // Clear command history before each test
    public function setUp(): void
    {
        $instance = HistoryService::getInstance();
        $reflectedInstance = new \ReflectionObject($instance);
        $history = $reflectedInstance->getProperty('history');
        $history->setAccessible(true);
        $history->setValue($instance, []);
    }

    public function testHistoryServiceIsSingleton(): void
    {
        // Get an HistoryService instance
        $instance = HistoryService::getInstance();

        // Assert that it is a subclass of Singleton
        $this->assertInstanceOf(Singleton::class, $instance);
    }

    public function testAddCommandAddsTheCommandToTheHistory(): void
    {
        // Get an HistoryService instance
        $instance = HistoryService::getInstance();

        // Add a command to the command history
        $cmd = 'ls -l';
        $instance->addCommand($cmd);

        // Expect the command to be included in the history
        $history = $instance->getHistory();
        $this->assertContains($cmd, $history);
    }
    
    public function testSearchCommandReturnsAllMatchesForString(): void
    {
        // Get an HistoryService instance
        $instance = HistoryService::getInstance();

        // Add a series of commands to the command history
        $commands = [
            'ls -l',
            'echo test',
            'ls /var/www',
            'cd /var/www',
            'ls'
        ];
        foreach ($commands as $cmd) {
            $instance->addCommand($cmd);
        }

        // Search for 'ls' commands
        $result = $instance->searchCommand('ls');

        // Expect all appearances of `ls` to be returned
        $this->assertEquals(['ls -l', 'ls /var/www', 'ls'], $result);
    }

    public function testHistoryCanBeCleaned(): void
    {
        // Get an HistoryService instance
        $instance = HistoryService::getInstance();

        // Add a series of commands to the command history
        $commands = [
            'ls -l',
            'echo test',
            'ls /var/www',
            'cd /var/www',
            'ls'
        ];
        foreach ($commands as $cmd) {
            $instance->addCommand($cmd);
        }

        // Clear command history
        $instance->clearHistory();

        // Expect all appearances of `ls` to be returned
        $history = $instance->getHistory();
        $this->assertEquals([], $history);
    }
}
?>
