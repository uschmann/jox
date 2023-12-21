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
            default:
                $this->errorReporter->error($this->line, 'Unexpected token');
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

    protected function isAtEnd(): bool
    {
        return $this->current >= mb_strlen($this->source);
    }

}