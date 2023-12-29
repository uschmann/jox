<?php

namespace Uschmann\Jox;

use Uschmann\Jox\Expression\Binary;
use Uschmann\Jox\Expression\Expr;
use Uschmann\Jox\Expression\Grouping;
use Uschmann\Jox\Expression\Literal;
use Uschmann\Jox\Expression\Unary;
use Uschmann\Jox\Expression\Variable;
use Uschmann\Jox\Statement\ExpressionStmt;
use Uschmann\Jox\Statement\PrintStmt;
use Uschmann\Jox\Statement\Stmt;
use Uschmann\Jox\Statement\VarStmt;

class Parser
{
    protected $current = 0;
    protected $tokens;

    public function __construct(protected ErrorReporter $errorReporter)
    {
    }

    /**
     * @return Stmt[]
     */
    public function parse($tokens): array
    {
        $this->tokens  = $tokens;
        $this->current = 0;
        $statements    = [];

        while (!$this->isAtEnd()) {
            $statements[] = $this->declaration();
        }

        return $statements;
    }

    public function parseExpression($tokens): Expr|null
    {
        $this->tokens  = $tokens;
        $this->current = 0;

        try {
            return $this->expression();
        } catch (ParseException $error) {
            return null;
        }
    }

    protected function declaration(): Stmt|null
    {
        try {
            if($this->match(Token::TYPE_VAR)) {
                return $this->varStatement();
            }

            return $this->statement();
        } catch (ParseException $parseException) {
            $this->synchronize();
            return null;
        }

    }

    protected function varStatement(): Stmt
    {
        $name = $this->consume(Token::TYPE_IDENTIFIER, "Expect variable name");

        $initializer = null;
        if($this->match(Token::TYPE_EQUAL)) {
            $initializer = $this->expression();
        }

        $this->consume(Token::TYPE_SEMICOLON, "Expect ';' after variable declaration");
        return new VarStmt($name, $initializer);
    }

    protected function statement(): Stmt
    {
        if($this->match(Token::TYPE_PRINT)) {
            return $this->printStatement();
        }

        return $this->expressionStatement();
    }

    protected function printStatement(): PrintStmt
    {
        $expression = $this->expression();
        $this->consume(Token::TYPE_SEMICOLON, "Expect ';' after value.");
        return new PrintStmt($expression);
    }

    protected function expressionStatement(): ExpressionStmt
    {
        $expression = $this->expression();
        $this->consume(Token::TYPE_SEMICOLON, "Expect ';' after expression.");
        return new ExpressionStmt($expression);
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
        $expr = $this->term();

        while ($this->match(Token::TYPE_GREATER, Token::TYPE_GREATER_EQUAL, Token::TYPE_LESS, Token::TYPE_LESS_EQUAL)) {
            $operator = $this->previous();
            $right    = $this->term();
            $expr     = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    protected function term(): Expr
    {
        $expr = $this->factor();

        while ($this->match(Token::TYPE_MINUS, Token::TYPE_PLUS)) {
            $operator = $this->previous();
            $right    = $this->factor();
            $expr     = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    protected function factor(): Expr
    {
        $expr = $this->unary();

        while ($this->match(Token::TYPE_SLASH, Token::TYPE_STAR)) {
            $operator = $this->previous();
            $right    = $this->unary();
            $expr     = new Binary($expr, $operator, $right);
        }

        return $expr;
    }

    protected function unary(): Expr
    {
        if ($this->match(Token::TYPE_BANG, Token::TYPE_MINUS)) {
            $operator = $this->previous();
            $right    = $this->unary();
            return new Unary($operator, $right);
        }

        return $this->primary();
    }

    protected function primary(): Expr
    {
        if ($this->match(Token::TYPE_FALSE)) return new Literal(false);
        if ($this->match(Token::TYPE_TRUE)) return new Literal(true);
        if ($this->match(Token::TYPE_NIL)) return new Literal(null);

        if ($this->match(Token::TYPE_NUMBER, Token::TYPE_STRING)) {
            return new Literal($this->previous()->literal);
        }

        if ($this->match(Token::TYPE_LEFT_PAREN)) {
            $expr = $this->expression();
            $this->consume(Token::TYPE_RIGHT_PAREN, "Expect ')' after expression.");
            return new Grouping($expr);
        }

        if($this->match(Token::TYPE_IDENTIFIER)) {
            return new Variable($this->previous());
        }

        throw $this->error($this->peek(), "Expect expression.");
    }

    private function consume($type, string $message): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }

        throw $this->error($this->peek(), $message);
    }

    protected function error(Token $token, string $message)
    {
        $this->errorReporter->parseError($token, $message);
        return new ParseException($message);
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

    protected function synchronize()
    {
        $this->advance();
        while (!$this->isAtEnd()) {
            if ($this->previous()->type === Token::TYPE_SEMICOLON) {
                return;
            }

            switch ($this->peek()->type) {
                case Token::TYPE_CLASS:
                case Token::TYPE_FOR:
                case Token::TYPE_FUN:
                case Token::TYPE_IF:
                case Token::TYPE_PRINT:
                case Token::TYPE_RETURN:
                case Token::TYPE_VAR:
                case Token::TYPE_WHILE:
                    return;
            }
        }

        $this->advance();
    }

}