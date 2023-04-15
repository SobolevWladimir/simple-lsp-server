<?php

namespace SimpleLspServer\Commands;

use SimpleLspServer\Core\Response;

interface CommandInterface {
  public function handle(array $parem):Response;

}
