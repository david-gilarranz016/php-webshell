<?php
namespace WebShell;

class HistoryService extends Singleton
{
    private $history = [];

    public function addCommand(string $cmd): void
    {
        array_push($this->history, $cmd);
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function searchCommand(string $cmd): array
    {
        // Filter the $history array looking for commands that start with $cmd
        $searchResults = array_filter($this->history, function ($savedCommand) use ($cmd)
        {
            return str_starts_with($savedCommand, $cmd);
        });

        // Reindex the array
        return array_values($searchResults);
    }

    public function clearHistory(): void
    {
        $this->history = [];
    }
}
?>
