<?php

declare(strict_types=1);

namespace Boilerwork\Support\Logs;

use DateTimeImmutable;
use Stringable;

class Logger
{
    public static function error(string|Stringable|array $message, ?string $path = null, string $exception = \Exception::class, ?string $channel = 'error'): void
    {
        if ($path === null) {
            $path = '/logs/';
        }

        $d = new DateTimeImmutable();
        $message = is_array($message) ? json_encode($message) : ((method_exists($message, '__toString')) ? $message->__toString() : $message);
        $message = '[' . $d->format(DateTimeImmutable::ATOM) . '] ' . strtoupper($exception) . ' ' . $message . PHP_EOL;

        if (env('APP_ENV') === 'LOCAL') {
            $fp = fopen(base_path($path) . $channel . '_' . $d->format('Y-m-d') . '.log', 'a');
        } else {
            $fp = fopen('php://stderr', 'w');
        }

        stream_set_blocking($fp, false);

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $message);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public static function logger(string|Stringable|array $message, ?string $path = null, string $mode = 'DEBUG', string $channel = 'default'): void
    {
        if ($path === null) {
            $path = '/logs/';
        }

        $d = new DateTimeImmutable();

        $message = is_array($message) ? json_encode($message) : ((method_exists($message, '__toString')) ? $message->__toString() : $message);
        $message = '[' . $d->format(DateTimeImmutable::ATOM) . '] ' . strtoupper($mode) . ' ' . $message . PHP_EOL;

        if (env('APP_ENV') === 'LOCAL') {
            $fp = fopen(base_path($path) . $channel . '_' . $d->format('Y-m-d') . '.log', 'a');
        } else {
            $fp = fopen('php://stdout', 'w');
        }

        stream_set_blocking($fp, false);

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $message);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
