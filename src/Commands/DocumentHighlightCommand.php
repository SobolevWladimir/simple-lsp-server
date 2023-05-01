<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\RequestMessage;

class DocumentHighlightCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/documentHighlight.json');

        $json = json_decode($jsonText, true);
        return $json;
    }
}
