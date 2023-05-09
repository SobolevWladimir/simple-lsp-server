<?php

namespace SimpleLspServer\Commands\TextDocument;

use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Entity\RequestMessage;

class CodeActionCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/codeAction.json');

        $json = json_decode($jsonText, true);
        return $json;
    }
}
