<?php

namespace Uschmann\Jox;

use Uschmann\Jox\Expression\AstPrinter;

class Lox
{
    private $hasError = false;

    public function __construct(
        protected Scanner $scanner,
        protected Parser  $parser
    )
    {
    }


    public function runFile(string $filename)
    {
        $source = file_get_contents($filename);
        $this->run($source);

        if ($this->hasError) {
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
        $tokens = $this->scanner->scanTokens($source);
        $expr   = $this->parser->parse($tokens);

        $astPrinter = new AstPrinter();
        var_dump($astPrinter->print($expr));

        //foreach ($tokens as $token) {
        //    var_dump($token->toString());
        //}
    }

}