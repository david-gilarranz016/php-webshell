<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class UploadFileActionTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testImplementsActionInterface(): void
    {
        // Get an action instance
        $action = new UploadFileAction;

        // Assert that it implements the interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testCreatesTheSpecifiedTextFileWithTheCorrectContents(): void
    {
        // Initialize variables
        $fd = 3;
        $filename = 'root/test.txt';
        $content = 'This is a test file.';

        // Mock fopen, fwrite and fclose, and add expectations
        $fopen = $this->getFunctionMock(__NAMESPACE__, 'fopen');
        $fopen->expects($this->once())->with($filename, 'w')->willReturn($fd);

        $fwrite = $this->getFunctionMock(__NAMESPACE__, 'fwrite');
        $fwrite->expects($this->once())->with($fd, $content);

        $fclose = $this->getFunctionMock(__NAMESPACE__, 'fclose');
        $fclose->expects($this->once())->with($fd);

        // Prepare the arguments and run the action
        $args = (object) [
            'filename' => $filename,
            'content' => base64_encode($content),
            'binary' => false
        ];
        $action = new UploadFileAction;
        $action->run($args);
    }

    public function testCreatesTheSpecifiedBinaryFileWithTheCorrectContents(): void
    {
        // Initialize variables
        $fd = 3;
        $filename = 'root/test.bin';
        $content = 'This is a test file.';

        // Mock fopen, fwrite and fclose, and add expectations
        $fopen = $this->getFunctionMock(__NAMESPACE__, 'fopen');
        $fopen->expects($this->once())->with($filename, 'wb')->willReturn($fd);

        $fwrite = $this->getFunctionMock(__NAMESPACE__, 'fwrite');
        $fwrite->expects($this->once())->with($fd, $content);

        $fclose = $this->getFunctionMock(__NAMESPACE__, 'fclose');
        $fclose->expects($this->once())->with($fd);

        // Prepare the arguments and run the action
        $args = (object) [
            'filename' => $filename,
            'content' => base64_encode($content),
            'binary' => true
        ];
        $action = new UploadFileAction;
        $action->run($args);
    }
}
?>
