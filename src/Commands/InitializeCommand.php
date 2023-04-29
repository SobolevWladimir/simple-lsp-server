<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\ResponseMessage;

class InitializeCommand implements CommandInterface
{
    public function execute(array $param): ResponseMessage
    {
        $jsonText = file_get_contents(__DIR__ . '/files/init.json');
        $json = json_decode($jsonText);

        $message  = new ResponseMessage(1, $json);
        return $message;
    }
}
