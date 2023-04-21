<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Entity\ResponseMessage;

class InitializeCommand implements CommandInterface
{
    public function execute(array $param): ResponseMessage
    {
        $message  = new ResponseMessage(1, []);
        return $message;
    }
}
