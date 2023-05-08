<?php

namespace SimpleLspServer\Commands\TextDocument;

use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Entity\RequestMessage;

class ComplectionCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/complection.json');

        $textDocument = $message->params['textDocument'];
        $uri = $textDocument['uri'];
        $json = json_decode($jsonText, true);
        $json['params']['uri'] = $uri;
        return $json;
    }
}
