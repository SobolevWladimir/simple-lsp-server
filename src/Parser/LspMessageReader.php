<?php

namespace SimpleLspServer\Parser;

use SimpleLspServer\Entity\RawMessage;

class LspMessageReader
{
    const HEADER_CONTENT_LENGTH = 'Content-Length';

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var null|array<string>
     */
    private $headers = null;

    public function __construct(private string $chunk)
    {
    }



    public function parse(): ?RawMessage
    {
        for ($i = 0; $i < strlen($this->chunk); $i++) {
            $this->buffer .= $this->chunk[$i];

            if (null !== $request = $this->parseRequest()) {
                return $request;
            }
        }

        return null;
    }

    private function parseRequest(): ?RawMessage
    {
        // start by parsing the headers:
        if (
            $this->headers === null && substr($this->buffer, -4, 4) === "\r\n\r\n"
        ) {
            $this->headers = $this->parseHeaders(
                substr($this->buffer, 0, -4)
            );
            $this->buffer = '';
            return null;
        }

        if (null === $this->headers) {
            return null;
        }

        // we finished parsing the headers, now parse the body
        if (!isset($this->headers[self::HEADER_CONTENT_LENGTH])) {
            throw new \Exception(sprintf(
                'Header did not contain mandatory Content-Length in "%s"',
                json_encode($this->headers)
            ));
        }

        $contentLength = (int) $this->headers[self::HEADER_CONTENT_LENGTH];

        if (strlen($this->buffer) !== $contentLength) {
            return null;
        }

        $request = new RawMessage($this->headers, $this->decodeBody($this->buffer));
        $this->buffer = '';
        $this->headers = null;

        return $request;
    }

    private function parseHeaders(string $rawHeaders): array
    {
        $lines = explode("\r\n", $rawHeaders);
        $headers = [];

        foreach ($lines as $line) {
            [ $name, $value ] = array_map(function ($value) {
                return trim($value);
            }, explode(':', $line));
            $headers[$name] = $value;
        }

        return $headers;
    }

    private function decodeBody(string $string): array
    {
        $array = json_decode($string, true);

        if (null === $array) {
            throw new \Exception(sprintf(
                'Could not decode "%s": %s',
                $string,
                json_last_error_msg()
            ));
        }

        if (!is_array($array)) {
            throw new \Exception(sprintf(
                'Expected an array got "%s"',
                gettype($array)
            ));
        }

        return $array;
    }
}
