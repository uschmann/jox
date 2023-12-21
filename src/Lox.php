<?php

namespace Uschmann\Jox;

class Lox
{
    private $hasError = false;

    public function __construct(protected Scanner $scanner)
    {
    }


    public function runFile(string $filename)
    {
        $source = file_get_contents($filename);
        $this->run($source);

        if($this->hasError) {
            exit(65);
        }
    }

    public function runPrompt()
    {
        while (true) {
            $line = readline('>');

            if (!$line) {
                break;
            }

            $this->run($line);

            $this->hasError = false;
        }
    }

    protected function run(string $source)
    {
        $tokens  = $this->scanner->scanTokens($source);

        foreach ($tokens as $token) {
            var_dump($token->toString());
        }
    }

}