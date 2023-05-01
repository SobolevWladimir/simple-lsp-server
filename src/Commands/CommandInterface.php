<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\RequestMessage;

interface CommandInterface
{
    public function execute(RequestMessage $message): array;
}
