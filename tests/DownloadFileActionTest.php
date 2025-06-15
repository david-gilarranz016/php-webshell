<?php
namespace WebShell;

use PHPUnit\Framework\TestCase;

class DownloadFileActionTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function tearDown(): void 
    {
        unset($_SESSION['cwd']);
    }

    public function testImplementsActionInterface(): void
    {
        // Create an action instance
        $action = new DownloadFileAction;

        // Assert it implements the Action interface
        $this->assertInstanceOf(Action::class, $action);
    }

    public function testReturnsBase64EncodedTextFile(): void
    {
        // Initialize variables
        $_SESSION['cwd'] = '/var/www/html';
        $fd = 3;
        $filename = 'test.txt';
        $content = 'This is a test file.';
        $size = 20;

        // Mock fopen, fread, filesize and fclose
        $fopen = $this->getFunctionMock(__NAMESPACE__, 'fopen');
        $fopen->expects($this->once())->with($_SESSION['cwd'] . '/' . $filename, 'r')->willReturn($fd);

        $filesize = $this->getFunctionMock(__NAMESPACE__, 'filesize');
        $filesize->expects($this->once())->with($_SESSION['cwd'] . '/' . $filename)->willReturn($size);

        $fread = $this->getFunctionMock(__NAMESPACE__, 'fread');
        $fread->expects($this->once())->with($fd, $size)->willReturn($content);

        $fclose = $this->getFunctionMock(__NAMESPACE__, 'fclose');
        $fclose->expects($this->once())->with($fd);

        // Call the DownloadFileAction
        $action = new DownloadFileAction;
        $args = (object) [
            'filename' => $filename,
            'binary' => false
        ];
        $result = $action->run($args);

        // Expect the result to be the base64 encoded file
        $this->assertEquals(base64_encode($content), $result);
    }

    public function testReturnsBase64EncodedBinaryFile(): void
    {
        // Initialize variables
        $_SESSION['cwd'] = '/var/www/html';
        $fd = 3;
        $filename = 'test.txt';
        $content = random_bytes(16);
        $size = 16;

        // Mock fopen, fread, filesize and fclose
        $fopen = $this->getFunctionMock(__NAMESPACE__, 'fopen');
        $fopen->expects($this->once())->with($_SESSION['cwd'] . '/' . $filename, 'rb')->willReturn($fd);

        $filesize = $this->getFunctionMock(__NAMESPACE__, 'filesize');
        $filesize->expects($this->once())->with($_SESSION['cwd'] . '/' . $filename)->willReturn($size);

        $fread = $this->getFunctionMock(__NAMESPACE__, 'fread');
        $fread->expects($this->once())->with($fd, $size)->willReturn($content);

        $fclose = $this->getFunctionMock(__NAMESPACE__, 'fclose');
        $fclose->expects($this->once())->with($fd);

        // Call the DownloadFileAction
        $action = new DownloadFileAction;
        $args = (object) [
            'filename' => $filename,
            'binary' => true
        ];
        $result = $action->run($args);

        // Expect the result to be the base64 encoded file
        $this->assertEquals(base64_encode($content), $result);
    }
}
?>
