<?php

namespace Uschmann\Jox;

class Scanner
{
    public function __construct(public ErrorReporter $errorReporter)
    {
    }


    public function scanTokens(string $script)
    {
        return [
            new Token(
                Token::TYPE_FUN,
                $script,
                null,
                1
            ),
        ];
    }

}