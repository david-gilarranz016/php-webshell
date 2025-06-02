<?php
namespace WebShell;

class DeleteHistoryAction implements Action
{
    public function run(array $args): string
    {
        // Delete history
        HistoryService::getInstance()->clearHistory();

        // Return empty string
        return '';
    }
}
?>
