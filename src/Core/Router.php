<?php 
namespace SimpleLspServer\Core;

use DefaultCommand;
use SimpleLspServer\Commands\CommandInterface;

class Router {


  public function route(string $method):CommandInterface{
    

    return new DefaultCommand();
  }

}
