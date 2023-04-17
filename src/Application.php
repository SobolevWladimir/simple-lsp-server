<?php

namespace SimpleLspServer;

use Amp\ByteStream\ReadableResourceStream;
use Amp\ByteStream\WritableResourceStream;


class Application
{
    public function run(): void
    {
        $input = new ReadableResourceStream(STDIN);
        $output  = new WritableResourceStream(STDOUT);


        while (($chunk = $input->read()) !== null) {
          //$output->write("Вы написали: ".$chunk);
        }
    }
  public function log(string $text) {

  }

}
