<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;
use SimpleLspServer\Parser\LspMessageReader;

class Application
{
    public function run(): void
    {
        $input = new ReadableResourceStream(STDIN);
        while (($chunk = $input->read()) !== null) {
            $reader  = new LspMessageReader($chunk);
            $message = $reader->parse();
            if (null != $message) {
              
                $this->logBody($message->toRequestMessage()->method);
            }
            $this->log($chunk);
        }
    }

    public function log(string $text): void
    {
        $file  = "/home/wladimir/lsp_test.txt";
        file_put_contents($file, $text, FILE_APPEND);
        file_put_contents($file, "\n\r -------- \n\r", FILE_APPEND);
    }

    public function logBody($body): void
    {
        $text = json_encode($body);
        $file  = "/home/wladimir/lsp_body_test.txt";
        file_put_contents($file, $text, FILE_APPEND);
        file_put_contents($file, "\n\r -------- \n\r", FILE_APPEND);
    }
}
