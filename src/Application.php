<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;
use Amp\ByteStream\WritableResourceStream;
use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Entity\Message;
use SimpleLspServer\Entity\RequestMessage;
use SimpleLspServer\Parser\LspMessageReader;
use SimpleLspServer\Transmitter\LspMessageFormatter;

class Application
{
    public ReadableResourceStream $input = new ReadableResourceStream(STDIN);
    public WritableResourceStream $output  = new WritableResourceStream(STDOUT);
    public LspMessageFormatter $fomatter  = new LspMessageFormatter();

    public function run(): void
    {

        while (($chunk = $this->input->read()) !== null) {
            $reader  = new LspMessageReader($chunk);
            $message = $reader->parse();
            if (null != $message) {
                $requestMessage  = $message->toRequestMessage();
                $command  = $this->route($requestMessage);
                if ($command !== null) {
                    $this->sendMessage($command->execute($requestMessage->params));
                } else {
                    $this->log($chunk);
                }
            }
        }
    }

    private function route(RequestMessage $message): ?CommandInterface
    {

        return null;
    }

    private function sendMessage(Message $message): void
    {
        $responseBody  = $this->fomatter->format($message);
        $this->output->write($responseBody);
    }

    public function log(string $text): void
    {
        $file  = "/home/wladimir/lsp_test.txt";
        file_put_contents($file, $text, FILE_APPEND);
        file_put_contents($file, "\n\r -------- \n\r", FILE_APPEND);
    }
}
