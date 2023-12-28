<?php

namespace Uschmann\Jox;

use JetBrains\PhpStorm\Pure;

class RuntimeError extends \RuntimeException
{
    #[Pure] public function __construct(public Token $token, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}