<?php

namespace Uschmann\Jox;

use Uschmann\Jox\Expression\AstPrinter;

class Lox
{
    private $hasError = false;

    public function __construct(
        protected Scanner     $scanner,
        protected Parser      $parser,
        protected Interpreter $interpreter,
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

        if($expr) {
            $result = $this->interpreter->interpret($expr);
            echo(json_encode($result) . "\n");
        }
        //$astPrinter = new AstPrinter();
        //echo($astPrinter->print($expr) . "\n");



        //foreach ($tokens as $token) {
        //    var_dump($token->toString());
        //}
    }

}