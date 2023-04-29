<?php

namespace SimpleLspServer\Commands;

class InitializedCommand implements CommandInterface
{
    public function execute(?array $param): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/initialized.json');
        $json = json_decode($jsonText, true);
        return $json;
    }
}
