<?php

namespace SimpleLspServer\Entity;

use SimpleLspServer\Entity\RequestMessage;

class RawMessage
{
    const HEADER_CONTENT_LENGTH = 'Content-Length';

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $body;

    public function __construct(array $headers, array $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function toRequestMessage(): RequestMessage
    {
        $id = $this->body['id'];
        $method = $this->body['method'];
        $param = $this->body['params'];
        return new RequestMessage($id, $method, $param);
    }
}
