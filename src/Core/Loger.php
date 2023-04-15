<?php

namespace SimpleLspServer\Core;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;

class Loger extends AbstractLogger implements LoggerInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    private function format(string $level, string $message, array $context, bool $prefixDate = true): string
    {
        if (str_contains($message, '{')) {
            $replacements = [];
            foreach ($context as $key => $val) {
                if (null === $val || \is_scalar($val) || $val instanceof \Stringable) {
                    $replacements["{{$key}}"] = $val;
                } elseif ($val instanceof \DateTimeInterface) {
                    $replacements["{{$key}}"] = $val->format(\DateTimeInterface::RFC3339);
                } elseif (\is_object($val)) {
                    $replacements["{{$key}}"] = '[object ' . $val::class . ']';
                } else {
                    $replacements["{{$key}}"] = '[' . \gettype($val) . ']';
                }
            }

            $message = strtr($message, $replacements);
        }

        $log = sprintf('[%s] %s', $level, $message);
        if ($prefixDate) {
            $log = date(\DateTimeInterface::RFC3339) . ' ' . $log;
        }

        return $log;
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $format  = $this->format($level, $message, $context);
        file_put_contents($this->file, $format, FILE_APPEND );
    }
}
