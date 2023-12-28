<?php

namespace Uschmann\Jox;

class ErrorReporter
{

    public function parseError(Token $token, string $message)
    {
        if($token->type === Token::TYPE_EOF) {
            $this->report($token->line, ' at end', $message);
        } else {
            $this->report($token->line, "at '{$token->lexeme}'", $message);
        }
    }

    public function error(int $line, string $message)
    {
        $this->report($line, '', $message);
    }

    public function runtimeError(RuntimeError $error)
    {
        echo "[{$error->token->line}] {$error->getMessage()}\n";
    }

    protected function report(int $line, string $where, string $message)
    {
        echo "[{$line}] Error {$where}: {$message}\n";
    }

}