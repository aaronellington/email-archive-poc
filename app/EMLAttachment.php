<?php

namespace App;

use ZBateson\MailMimeParser\Message\IMessagePart;

class EMLAttachment
{
    public readonly string $name;

    public readonly string $content;

    public static function parse(IMessagePart $resource): self
    {
        $result = new self();
        $result->name = (string) $resource->getFilename();
        $result->content = (string) $resource->getContent();

        return $result;
    }
}
