<?php

namespace SimpleLspServer\Commands\TextDocument;

use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Entity\RequestMessage;

class DocumentHighlightCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/documentHighlight.json');

        $json = json_decode($jsonText, true);
        $json['id'] = $message->id;

        return $json;
    }
}
