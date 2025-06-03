<?php
namespace WebShell;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class DownloadFileActionTest extends TestCase
{
    // Virtual filesystem's root 
    private $root;

    // Set up virutal filesystem
    public function setUp(): void
    {
        $this->root = vfsStream::setup('root');
    }

    public function testImplementsActionInterface(): void
    {
        // Create an action instance
        $action = new DownloadFileAction;

        // Assert it implements the Action interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testReturnsBase64EncodedFile(): void
    {
        // Create test file
        $filename = vfsStream::url('root/test.txt');
        $content = 'This is a test file.';
        $fd = fopen($filename, 'w');
        fwrite($fd, $content);

        // Call the DownloadFileAction
        $action = new DownloadFileAction;
        $args = (object) [
            'filename' => $filename
        ];
        $result = $action->run($args);

        // Expect the result to be the base64 encoded file
        $this->assertEquals(base64_encode($content), $result);
    }
}
?>
