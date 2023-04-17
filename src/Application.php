<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;

class Application
{
    public function run(): void
    {
       $input = new ReadableResourceStream(STDIN);
        while (($chunk = $input->read()) !== null) {
            $this->log($chunk);
        }
    }

    public function log(string $text):void
    {
        $file  = "~/lsp_test.txt";
        file_put_contents($file, $text, FILE_APPEND);
    }
}
