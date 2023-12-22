<?php

namespace Uschmann\Jox;

class ErrorReporter
{

    public function error(int $line, string $message)
    {
        $this->report($line, '', $message);
    }

    protected function report(int $line, string $where, string $message)
    {
        echo "[{$line}] Error {$where}: {$message}\n";
    }

}