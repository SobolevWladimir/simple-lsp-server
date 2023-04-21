<?php

namespace SimpleLspServer\Transmitter;

use Exception;
use SimpleLspServer\Entity\Message;
use SimpleLspServer\Entity\ResponseMessage;

class LspMessageFormatter
{
    public function format(Message $message): string
    {
        $body = $this->serialize($message);

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
    public function serialize(Message $message): string
    {
        $data = $this->normalize($message);
        if (!is_array($data)) {
            throw new Exception('Expected an array');
        }
        if ($message instanceof ResponseMessage) {
            $data = $this->ensureOnlyResultOrErrorSet($data);
        }
        $decoded = json_encode($data);

        if (false === $decoded) {
            throw new Exception(sprintf(
                'Could not encode JSON: "%s"',
                \json_last_error_msg()
            ));
        }

        return $decoded;
    }

    private function ensureOnlyResultOrErrorSet(array $data): array
    {
        if (array_key_exists('error', $data) && array_key_exists('result', $data)) {
            unset($data['result']);
            return $data;
        }

        if (!array_key_exists('error', $data) && !array_key_exists('result', $data)) {
            $data['result'] = null;
        }

        return $data;
    }


    /**
     * Normalize a message before being serialized by recursively applying array_filter
     * and removing null values
     *
     * @param mixed $message
     */
    public function normalize($message): mixed
    {
        if (is_object($message)) {
            $message = (array) $message;
        }

        if (!is_array($message)) {
            return $message;
        }

        return array_filter(array_map([$this, 'normalize'], $message), function ($value) {
            return $value !== null;
        });
    }
}
