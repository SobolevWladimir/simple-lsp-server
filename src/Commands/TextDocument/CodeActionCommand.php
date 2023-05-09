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
        $json['id'] = $message->id;
        $json['result'][0]['command']['arguments']  = [
          "add_word",
          $message->params['textDocument']['uri'],
        ];
        return $json;
    }
}
