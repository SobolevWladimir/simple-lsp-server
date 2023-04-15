<?php

namespace SimpleLspServer\StreamProvider;

use Amp\Promise;
use Amp\Success;
use Psr\Log\LoggerInterface;
use SimpleLspServer\Stream\ResourceDuplexStream;

final class ResouceStreamProvider implements StreamProvider
{
    /**
     * @var ResourceDuplexStream
     */
    private $duplexStream;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $provided = false;

    public function __construct(ResourceDuplexStream $duplexStream, LoggerInterface $logger)
    {
        $this->duplexStream = $duplexStream;
        $this->logger = $logger;
    }

    /**
     * @return Success<null|Connection>
     */
    public function accept(): Promise
    {
        // resource connections are valid only for
        // the length of the client connnection
        if ($this->provided) {
            return new Success(null);
        }

        $this->provided = true;

        $this->logger->info('Listening on STDIO');

        return new Success(new Connection('stdio', $this->duplexStream));
    }

    public function close(): void
    {
        $this->duplexStream->close();
    }
}

