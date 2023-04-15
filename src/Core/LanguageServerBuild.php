<?php

namespace SimpleLspServer\Core;


use Amp\ByteStream\ResourceInputStream;
use Amp\ByteStream\ResourceOutputStream;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class LanguageServerBuild
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ServerStats|null
     */
    private $stats = null;

    private function __construct(
        Router $router,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->router = $router;
    }

    /**
     * Create a new instance of the builder \o/
     */
    public static function create(
        Router $router,
        LoggerInterface $logger = null
    ): self {
        return new self(
            $router,
            $logger ?: new NullLogger()
        );
    }



    public function build(): LanguageServer
    {
            $provider = new ResourceStreamProvider(
                new ResourceDuplexStream(
                    new ResourceInputStream(STDIN),
                    new ResourceOutputStream(STDOUT)
                ),
                $this->logger
            );

        return new LanguageServer(
            $this->dispatcherFactory,
            $this->logger,
            $provider,
            new RequestInitializer(),
            $this->stats ?: new ServerStats()
        );
    }
}

