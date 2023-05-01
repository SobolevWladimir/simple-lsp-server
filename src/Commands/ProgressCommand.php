<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\RequestMessage;

class ProgressCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/progress.json');
        $json = json_decode($jsonText, true);
        return $json;
    }
}
