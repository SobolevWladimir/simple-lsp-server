<?php

namespace SimpleLspServer\Commands;

class InitializeCommand implements CommandInterface
{
    public function execute(array $param): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/init.json');
        $json = json_decode($jsonText);

        return $json;
    }
}
