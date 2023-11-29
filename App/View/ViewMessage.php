<?php

namespace App\View;

class ViewMessage implements CommitMessage {
    private $title = '';
    private $taskId = null;
    private $tags, $details, $bcBreaks, $todos = [];

    public function __construct($title, $taskId, $tags, $details, $bcBreaks, $todos) {
        $this->title = $title;
        $this->taskId = $taskId;
        $this->tags = $tags;
        $this->details = $details;
        $this->bcBreaks = $bcBreaks;
        $this->todos = $todos;
    }
    
    public function getTitle(): string {
        return $this->title;
    }
    public function getTaskId(): ?int {
        return $this->taskId;
    }
    public function getTags(): array {
        return $this->tags;
    }
    public function getDetails(): array {
        return $this->details;
    }
    public function getBCBreaks(): array {
        return $this->bcBreaks;
    }
    public function getTodos(): array {
        return $this->todos;
    }
}