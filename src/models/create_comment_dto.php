<?php

class CreateCommentDto
{
    public string $message_id;
    public string $created_at;
    public string $text;

    public function __construct(string $message_id, string $created_at, string $text)
    {
        $this->message_id = $message_id;
        $this->created_at = $created_at;
        $this->text = $text;
    }
}
