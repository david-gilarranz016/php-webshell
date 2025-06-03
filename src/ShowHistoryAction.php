<?php
namespace WebShell;

class ShowHistoryAction implements Action
{
    public function run(object $args): string
    {
        // Get command history
        $instance = HistoryService::getInstance();
        $history = [];

        // Get full or filtered history depending on the args
        if (property_exists($args, 'search')) {
            $history = $instance->searchCommand($args->search);
        } else {
            $history = $instance->getHistory();
        }

        // Return history
        return implode("\n", $history);
    }
}
?>
