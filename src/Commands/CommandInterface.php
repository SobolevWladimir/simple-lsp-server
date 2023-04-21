<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\ResponseMessage;

interface CommandInterface
{
    public function execute(array $param): ResponseMessage;
}
