<?php
namespace WebShell;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class UploadFileActionTest extends TestCase
{
    // Virtual filesystem's root directory
    private $root;

    // Initialize virtual filesystem
    public function setUp(): void
    {
        $this->root = vfsStream::setup('root');
    }

    public function testImplementsActionInterface(): void
    {
        // Get an action instance
        $action = new UploadFileAction;

        // Assert that it implements the interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testCreatesTheSpecifiedFileWithTheCorrectContents(): void
    {
        // Expected file's name and content
        $filename = vfsStream::url('root/test.txt');
        $content = 'This is a test file.';

        // Prepare the arguments and run the action
        $args = (object) [
            'filename' => $filename,
            'content' => base64_encode($content)
        ];
        $action = new UploadFileAction;
        $action->run($args);

        // Verify that a file was created with the expected contents
        $createdContent = file_get_contents($filename);
        $this->assertEquals($content, $createdContent);
    }
}
?>
