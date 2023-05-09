<?php

namespace SimpleLspServer\Commands\Workspace;

use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Entity\RequestMessage;

class ApplyEditCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/applyEdit.json');
        $arguments  = $message->params['arguments'];
        $file = $arguments[1];
        $text = file_get_contents($file);
        $text  = "Привет " . $text;
        //file_put_contents($file, $text);
        $json = json_decode($jsonText, true);
        $json['params']['edit']['changes'][$file] = [
        [
          'range' => [
            'start' => ['line' => 0, 'character' => 0],
            'end' => ['line' => 0, 'character' => strlen($text)]
          ],
          "newText" => $text,
          ],
        ];
        return $json;
    }
}
