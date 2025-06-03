<?php
namespace WebShell;

class DeleteHistoryAction implements Action
{
    public function run(object $args): string
    {
        // Delete history
        HistoryService::getInstance()->clearHistory();

        // Return empty string
        return '';
    }
}
?>
