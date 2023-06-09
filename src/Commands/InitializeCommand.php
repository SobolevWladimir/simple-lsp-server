<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\RequestMessage;

class InitializeCommand implements CommandInterface
{
    public function execute(RequestMessage $message): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/init.json');
        $json = json_decode($jsonText, true);

        return $json;
    }
}
