<?php

namespace Uschmann\Jox;

use Uschmann\Jox\Expression\Binary;
use Uschmann\Jox\Expression\Expr;

class Parser
{
    protected $current = 0;

    public function __construct(protected array $tokens)
    {
    }

    protected function expression(): Expr
    {
        return $this->equality();
    }

    protected function equality(): Expr
    {
        $expr = $this->comparison();

        while ($this->match(Token::TYPE_BANG_EQUAL, Token::TYPE_EQUAL_EQUAL)) {
            $operator = $this->previous();
            $right    = $this->comparison();
            $expr     = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    protected function comparison(): Expr
    {

    }

    protected function match(...$types): bool
    {
        foreach ($types as $type) {
            if ($this->check($type)) {
                $this->advance();
                return true;
            }
        }

        return false;
    }

    protected function advance()
    {
        if (!$this->isAtEnd()) {
            $this->current++;
        }
        return $this->previous();
    }

    protected function isAtEnd(): bool
    {
        return $this->peek()->type === Token::TYPE_EOF;
    }

    protected function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }

    protected function check($type): bool
    {
        if ($this->isAtEnd()) {
            return false;
        }

        return $this->peek()->type === $type;
    }

    protected function peek(): Token
    {
        return $this->tokens[$this->current];
    }

}