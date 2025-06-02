<?php
namespace WebShell;

class ShowHistoryAction implements Action
{
    public function run(array $args): string
    {
        // Get command history
        $instance = HistoryService::getInstance();
        $history = [];

        // Get full or filtered history depending on the args
        if (array_key_exists('search', $args)) {
            $history = $instance->searchCommand($args['search']);
        } else {
            $history = $instance->getHistory();
        }

        // Return history
        return implode("\n", $history);
    }
}
?>
