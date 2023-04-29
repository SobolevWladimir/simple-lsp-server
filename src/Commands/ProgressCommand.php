<?php

namespace SimpleLspServer\Commands;

class ProgressCommand implements CommandInterface
{
    public function execute(?array $param): array
    {
        $jsonText = file_get_contents(__DIR__ . '/files/progress.json');
        $json = json_decode($jsonText, true);
        return $json;
    }
}
