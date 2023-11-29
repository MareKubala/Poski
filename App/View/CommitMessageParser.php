<?php

namespace App\View;

interface CommitMessageParser {
    public function parse(string $message): CommitMessage;
}