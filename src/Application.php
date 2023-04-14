<?php

namespace SimpleLspServer;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct('SimpleLspServer','1.0.0');
    }
}
