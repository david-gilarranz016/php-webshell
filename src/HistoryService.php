<?php
namespace WebShell;

class HistoryService extends Singleton
{
    private $history = [];

    public function addCommand($cmd)
    {
        array_push($this->history, $cmd);
    }

    public function getHistory()
    {
        return $this->history;
    }

    public function searchCommand($cmd)
    {
        // Filter the $history array looking for commands that start with $cmd
        $searchResults = array_filter($this->history, function ($savedCommand) use ($cmd)
        {
            return str_starts_with($savedCommand, $cmd);
        });

        // Reindex the array
        return array_values($searchResults);
    }

    public function clearHistory()
    {
        $this->history = [];
    }
}
?>
