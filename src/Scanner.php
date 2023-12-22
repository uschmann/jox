<?php

namespace Uschmann\Jox;

class Scanner
{
    protected int $start   = 0;
    protected int $current = 0;
    protected int $line    = 1;

    protected string $source;
    protected array  $tokens;

    public function __construct(public ErrorReporter $errorReporter)
    {
    }

    public function scanTokens(string $source)
    {
        $this->source  = $source;
        $this->start   = 0;
        $this->current = 0;
        $this->tokens  = [];

        while (!$this->isAtEnd()) {
            // We are at the beginning of the next lexeme.
            $this->start = $this->current;
            $this->scanToken();
        }

        $this->tokens[] = new Token(Token::TYPE_EOF, '', null, $this->line);

        return $this->tokens;
    }

    protected function scanToken()
    {
        $character = $this->advance();

        switch ($character) {
            // Single character tokens
            case '(':
                $this->addToken(Token::TYPE_LEFT_PAREN);
                break;
            case ')':
                $this->addToken(Token::TYPE_RIGHT_PAREN);
                break;
            case '{':
                $this->addToken(Token::TYPE_LEFT_BRACE);
                break;
            case '}':
                $this->addToken(Token::TYPE_RIGHT_BRACE);
                break;
            case ',':
                $this->addToken(Token::TYPE_COMMA);
                break;
            case '.':
                $this->addToken(Token::TYPE_DOT);
                break;
            case '-':
                $this->addToken(Token::TYPE_MINUS);
                break;
            case '+':
                $this->addToken(Token::TYPE_PLUS);
                break;
            case ';':
                $this->addToken(Token::TYPE_SEMICOLON);
                break;
            case '*':
                $this->addToken(Token::TYPE_STAR);
                break;
            // Single and double character tokens
            case '!':
                $this->addToken($this->match('=') ? Token::TYPE_BANG_EQUAL : Token::TYPE_BANG);
                break;
            case '=':
                $this->addToken($this->match('=') ? Token::TYPE_EQUAL_EQUAL : Token::TYPE_EQUAL);
                break;
            case '<':
                $this->addToken($this->match('=') ? Token::TYPE_LESS_EQUAL : Token::TYPE_LESS);
                break;
            case '>':
                $this->addToken($this->match('=') ? Token::TYPE_GREATER_EQUAL : Token::TYPE_GREATER);
                break;
            default:
                $this->errorReporter->error($this->line, "Unexpected token: {$character}");
                break;
        }
    }

    protected function addToken(string $type, $literal = null)
    {
        $text           = substr($this->source, $this->start, $this->current - $this->start);
        $this->tokens[] = new Token($type, $text, $literal, $this->line);
    }

    protected function advance(): string
    {
        return $this->source[$this->current++];
    }

    protected function match(string $expected): bool
    {
        if($this->isAtEnd()) {
            return false;
        }

        if($this->source[$this->current] !== $expected) {
            return false;
        }

        $this->current ++;
        return true;
    }

    protected function isAtEnd(): bool
    {
        return $this->current >= mb_strlen($this->source);
    }

}