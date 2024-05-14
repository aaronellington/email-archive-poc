<?php

namespace App;

use Exception;
use ZBateson\MailMimeParser\Header\HeaderConsts;
use ZBateson\MailMimeParser\MailMimeParser;

class EML
{
    public readonly string $html;

    public readonly string $subject;

    public readonly string $to;

    public readonly string $from;

    /** @var EMLAttachment[] */
    public readonly array $attachments;

    public static function parse(mixed $resource): self
    {
        $mailParser = new MailMimeParser();
        $message = $mailParser->parse($resource, false);

        $result = new self();
        $result->html = (string) $message->getHtmlContent();
        $result->from = (string) $message->getHeaderValue(HeaderConsts::FROM);
        $result->to = (string) $message->getHeaderValue(HeaderConsts::TO);
        $result->subject = (string) $message->getHeaderValue(HeaderConsts::SUBJECT);

        if (! $result->html || ! $result->from || ! $result->to || ! $result->subject) {
            throw new Exception('Invalid EML file provided');
        }

        $attachments = [];
        foreach ($message->getAllAttachmentParts() as $x) {
            $attachments[] = EMLAttachment::parse($x);
        }
        $result->attachments = $attachments;

        return $result;
    }
}
