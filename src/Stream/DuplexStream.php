<?php

namespace SimpleLspServer\Stream;

use Amp\ByteStream\InputStream;
use Amp\ByteStream\OutputStream;

interface DuplexStream extends InputStream, OutputStream
{
}

