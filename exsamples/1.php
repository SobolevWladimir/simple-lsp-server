<?php

use Amp\ByteStream\ReadableResourceStream;
use Amp\ByteStream\WritableResourceStream;

// Простое считывение с терминала и запись значение обратно
function run()
{
    $input = new ReadableResourceStream(STDIN);
    $output  = new WritableResourceStream(STDOUT);

    while (($chunk = $input->read()) !== null) {
        $output->write("Вы написали: " . $chunk);
    }
}
