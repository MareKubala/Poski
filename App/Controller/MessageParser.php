<?php

namespace App\Controller;

use App\View\ViewMessage;
use App\View\CommitMessage;
use App\View\CommitMessageParser;

class MessageParser implements CommitMessageParser {
    private $title = '';
    private $taskId = null;
    private $tags = [];
    private $details = [];
    private $bcBreaks = [];
    private $todos = [];

    public const REGEX_TITLE_WHITESPACE = "/\s([A-Za-z].+)$/m";
	public const REGEX_TITLE = "/^([A-Za-z].+)$/m";
    public const REGEX_TASK_ID = "/#(\d+)/";
    public const REGEX_TAGS = "/\[([^]]+)\]/";

    public function parse(string $message): CommitMessage {
        $lines = explode("\n", $message);

        foreach ($lines as $x => $line) {
            $line = trim($line);
            $x == 0 ? $this->parseFirstLine($line) : $this->parseOtherLines($line);
        }

        return new ViewMessage($this->title, $this->taskId, $this->tags, $this->details, $this->bcBreaks, $this->todos);
    }
    public function parseFirstLine($line) {
        $this->tags = $this->parseFirstLineValues($line, self::REGEX_TAGS);
        $this->taskId = $this->parseFirstLineValues($line, self::REGEX_TASK_ID)[0] ?? null;
        $this->title = $this->parseFirstLineValues($line, self::REGEX_TITLE_WHITESPACE)[0] ?? '';
		if($this->title == ''){
			$this->title = $this->parseFirstLineValues($line, self::REGEX_TITLE)[0] ?? '';
		}
    }
    public function parseFirstLineValues(string $line, string $regex): array {
        $matches = [];
        preg_match_all($regex, $line, $matches);
        $matches = array_map(function($match){
            return rtrim($match);
        }, $matches[1] ?? []);

        return $matches;
    }
    public function parseOtherLines($line) {
        if (str_starts_with($line, '* ')) {
            $this->details[] = substr($line, 2);
        } else if (str_starts_with($line, 'BC: ')) {
            $this->bcBreaks[] = substr($line, 4);
        } else if (str_starts_with($line, 'TODO: ')) {
            $this->todos[] = substr($line, 6);
        }
    }
}
