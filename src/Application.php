<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;
use Amp\ByteStream\WritableResourceStream;
use SimpleLspServer\Commands\CommandInterface;
use SimpleLspServer\Commands\TextDocument\CodeActionCommand;
use SimpleLspServer\Commands\TextDocument\DocumentHighlightCommand;
use SimpleLspServer\Commands\InitializeCommand;
use SimpleLspServer\Commands\InitializedCommand;
use SimpleLspServer\Commands\TextDocument\ComplectionCommand;
use SimpleLspServer\Commands\Workspace\ApplyEditCommand;
use SimpleLspServer\Entity\RequestMessage;
use SimpleLspServer\Parser\LspMessageReader;

class Application
{
    public ReadableResourceStream $input ;
    public WritableResourceStream $output ;

    public array $commands  = [
    '' => null,
    'initialize' => InitializeCommand::class,
    'initialized' => InitializedCommand::class,
    'shutdown' => null,
    'textDocument/didOpen' => null,
    'textDocument/didClose' => null,
    'textDocument/didChange' => null,
    'textDocument/documentHighlight' => DocumentHighlightCommand::class,
    'textDocument/completion' => ComplectionCommand::class,
    '$/cancelRequest' => null,
    'textDocument/codeAction' => CodeActionCommand::class,
    'workspace/executeCommand' => ApplyEditCommand::class,
    ];

    public function __construct()
    {
        $this->input = new ReadableResourceStream(STDIN);
        $this->output = new WritableResourceStream(STDOUT);
    }

    public function run(): void
    {
        try {
            while (($chunk = $this->input->read()) !== null) {
                try {
                    $this->handlingMessaage($chunk);
                } catch (\Throwable $e) {
                    $this->log($e->getMessage(), 'error');
                    $this->log($chunk, 'error input',);
                }
            }
        } catch (\Throwable $e) {
                    $this->log($e->getMessage(), 'total error');
                    $this->log($chunk, 'error input',);
        }
    }

    private function handlingMessaage(string $chunk): void
    {
        $reader  = new LspMessageReader($chunk);
        $message = $reader->parse();
        if (null === $message) {
            return;
        }

        $requestMessage  = $message->toRequestMessage();
        try {
            $command  = $this->route($requestMessage);
        } catch (\Exception $e) {
            $this->log($chunk, $e->getMessage());
        }

        if ($command !== null) {
            $this->log($chunk, 'input');
            $this->sendMessage($command->execute($requestMessage));
        } else {
            $this->log($chunk, 'not-anwer');
        }
    }

    private function route(RequestMessage $message): ?CommandInterface
    {
        $method  = $message->method;
        if (!array_key_exists($method, $this->commands)) {
             throw new \Exception('not found answer');
        }

        $className  = $this->commands[$method];
        if ($className == null) {
            return null;
        }

        return new $className();
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
