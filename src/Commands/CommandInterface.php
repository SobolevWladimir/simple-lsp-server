<?php

namespace SimpleLspServer\Commands;

interface CommandInterface
{
    public function execute(?array $param): array;
}
