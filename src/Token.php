<?php

namespace Uschmann\Jox;

class Token
{
    // Single-character tokens
    const TYPE_LEFT_PAREN  = 'LEFT_PAREN';
    const TYPE_RIGHT_PAREN = 'RIGHT_PAREN';
    const TYPE_LEFT_BRACE  = 'LEFT_BRACE';
    const TYPE_RIGHT_BRACE = 'RIGHT_BRACE';
    const TYPE_COMMA       = 'COMMA';
    const TYPE_DOT         = 'DOT';
    const TYPE_MINUS       = 'MINUS';
    const TYPE_PLUS        = 'PLUS';
    const TYPE_SEMICOLON   = 'SEMICOLON';
    const TYPE_SLASH       = 'SLASH';
    const TYPE_STAR        = 'STAR';

    // One or two character tokens
    const TYPE_BANG          = 'BANG';
    const TYPE_BANG_EQUAL    = 'BANG_EQUAL';
    const TYPE_EQUAL         = 'EQUAL';
    const TYPE_EQUAL_EQUAL   = 'EQUAL_EQUAL';
    const TYPE_GREATER       = 'GREATER';
    const TYPE_GREATER_EQUAL = 'GREATER_EQUAL';
    const TYPE_LESS          = 'LESS';
    const TYPE_LESS_EQUAL    = 'LESS_EQUAL';

    // Literals
    const TYPE_IDENTIFIER = 'IDENTIFIER';
    const TYPE_STRING     = 'STRING';
    const TYPE_NUMBER     = 'NUMBER';

    // Keywords
    const TYPE_AND    = 'AND';
    const TYPE_CLASS  = 'CLASS';
    const TYPE_ELSE   = 'ELSE';
    const TYPE_FALSE  = 'FALSE';
    const TYPE_FUN    = 'FUN';
    const TYPE_FOR    = 'FOR';
    const TYPE_IF     = 'IF';
    const TYPE_NIL    = 'NIL';
    const TYPE_OR     = 'OR';
    const TYPE_PRINT  = 'PRINT';
    const TYPE_RETURN = 'RETURN';
    const TYPE_SUPER  = 'SUPER';
    const TYPE_THIS   = 'THIS';
    const TYPE_TRUE   = 'TRUE';
    const TYPE_VAR    = 'VAR';
    const TYPE_WHILE  = 'WHILE';

    const TYPE_EOF = 'EOF';

    public function __construct(
        public string $type,
        public string $lexeme,
        public        $literal,
        public int    $line
    )
    {
    }

    public function toString(): string
    {
        return "{$this->type} {$this->lexeme} {$this->literal}";
    }
}