<?php 
namespace SimpleLspServer\Commands;

use SimpleLspServer\Core\Response;

class DefaultCommand implements CommandInterface {

  public function handle(array $parem): Response {
    return new Response();
  }

}
