<?php

class BaseMessageReplyType
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

class CommentDto extends BaseMessageReplyType
{
    public string $comment_id;
    public string $message_id;
    public string $created_at;
    public string $text;

    public function __construct(string $comment_id, string $message_id, string $created_at, string $text)
    {
        parent::__construct($message_id, $created_at, $text);
        $this->comment_id = $comment_id;
    }
}

class ReplyDto extends BaseMessageReplyType
{
    public string $reply_id;

    public function __construct(string $reply_id, string $message_id, string $created_at, string $text)
    {
        parent::__construct($message_id, $created_at, $text);
        $this->reply_id = $reply_id;
    }
}

class ReportDto extends BaseMessageReplyType
{
    public string $report_id;

    public function __construct(string $report_id, string $message_id, string $created_at, string $text)
    {
        parent::__construct($message_id, $created_at, $text);
        $this->report_id = $report_id;
    }
}
