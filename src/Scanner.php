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
        $this->line    = 0;
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
            case '/':
                if ($this->match('/')) {
                    while ($this->peek() !== "\n" && !$this->isAtEnd()) {
                        $this->advance();
                    }
                } else {
                    $this->addToken(Token::TYPE_SLASH);
                }
                break;
            case ' ':
            case "\r":
            case "\t":
                // ignore whitespace
                break;
            case "\n":
                $this->line++;
                break;
            case '"':
                $this->string();
                break;
            default:
                if($this->isDigit($character)) {
                    $this->number();
                } else {
                    $this->errorReporter->error($this->line, "Unexpected token: {$character}");
                }
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

    protected function number()
    {
        while($this->isDigit($this->peek())) {
            $this->advance();
        }

        if($this->peek() === '.' && $this->isDigit($this->peekNext())) {
            // Consume the .
            $this->advance();

            while($this->isDigit($this->peek())) {
                $this->advance();
            }
        }

        $value = (float) mb_substr($this->source, $this->start, $this->current - $this->start);
        $this->addToken(Token::TYPE_NUMBER, $value);
    }

    protected function string()
    {
        while($this->peek() !== '"' && !$this->isAtEnd()) {
            if($this->peek() === "\n") {
                $this->line ++;
            }

            $this->advance();
        }

        if($this->isAtEnd()) {
            $this->errorReporter->error($this->line, 'Unterminated string.');
            return;
        }

        $this->advance(); // Consume the closing "

        $value = mb_substr($this->source, $this->start + 1, $this->current - 1 - $this->start - 1);
        $this->addToken(Token::TYPE_STRING, $value);
    }

    protected function peek(): string
    {
        if ($this->isAtEnd()) {
            return "\0";
        }

        return $this->source[$this->current];
    }

    protected function peekNext(): string
    {
        if($this->current + 1 >= strlen($this->source)) {
            return "\0";
        }

        return $this->source[$this->current + 1];
    }

    protected function match(string $expected): bool
    {
        if ($this->isAtEnd()) {
            return false;
        }

        if ($this->source[$this->current] !== $expected) {
            return false;
        }

        $this->current++;
        return true;
    }

    protected function isAtEnd(): bool
    {
        return $this->current >= mb_strlen($this->source);
    }

    protected function isDigit(string $character): bool
    {
        return is_numeric($character);
    }

}