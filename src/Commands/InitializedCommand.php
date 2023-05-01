<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\RequestMessage;

class InitializedCommand implements CommandInterface
{
    public function execute(RequestMessage $mesage): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/initialized.json');
        $json = json_decode($jsonText, true);
        return $json;
    }
}
