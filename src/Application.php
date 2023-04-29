<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;
use Amp\ByteStream\WritableResourceStream;
use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Commands\InitializeCommand;
use SimpleLspServer\Commands\InitializedCommand;
use SimpleLspServer\Entity\RequestMessage;
use SimpleLspServer\Parser\LspMessageReader;

class Application
{
    public ReadableResourceStream $input ;
    public WritableResourceStream $output ;

    public array $commands  = [
    'initialize' => InitializeCommand::class,
    'initialized' => InitializedCommand::class,
    ];

    public function __construct()
    {
        $this->input = new ReadableResourceStream(STDIN);
        $this->output = new WritableResourceStream(STDOUT);
    }

    public function run(): void
    {

        while (($chunk = $this->input->read()) !== null) {
            try {
                $this->handlingMessaage($chunk);
            } catch (\Throwable $e) {
                $this->log($e->getMessage(), 'error');
            }
        }
    }

    private function handlingMessaage(string $chunk): void
    {
        $reader  = new LspMessageReader($chunk);
        $message = $reader->parse();
        if (null != $message) {
            $requestMessage  = $message->toRequestMessage();
            $command  = $this->route($requestMessage);
            if ($command !== null) {
                $this->log($chunk, 'input');
                $this->sendMessage($command->execute($requestMessage->params));
            } else {
                $this->log($chunk, 'not-anwer');
            }
        }
    }

    private function route(RequestMessage $message): ?CommandInterface
    {
        $method  = $message->method;
        if (array_key_exists($method, $this->commands)) {
            $className  = $this->commands[$method];
             return new $className();
        }

        return null;
    }

    private function sendMessage(array $message): void
    {
        $responseBody  = $this->format($message);
        $this->log($responseBody, 'output');
        $this->output->write($responseBody);
    }

    public function format(array $message): string
    {
        $body = json_encode($message);

        $headers = [
          'Content-Type: application/vscode-jsonrpc; charset=utf8',
          'Content-Length: ' . strlen($body),
        ];

        return implode('', [
          implode("\r\n", $headers),
          "\r\n\r\n",
          $body
        ]);
    }

    public function log(string $text, string $type): void
    {
        $file  = "/home/wladimir/lsp_test.txt";
        file_put_contents($file, "\n\r --$type-- \n\r", FILE_APPEND);
        file_put_contents($file, $text, FILE_APPEND);
        file_put_contents($file, "\n\r -- end $type -- \n\r", FILE_APPEND);
    }
}
