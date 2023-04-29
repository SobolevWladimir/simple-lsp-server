<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\ResponseMessage;

class InitializedCommand implements CommandInterface
{
    public function execute(array $param): ResponseMessage
    {
        $jsonText = file_get_contents(__DIR__ . '/files/initialized.json');
        $json = json_decode($jsonText);

        $message  = new ResponseMessage(1, $json);
        return $message;
    }
}
