<?php

namespace Uschmann\Jox;

class App
{
    public function __construct(protected Lox $lox)
    {
    }


    public function run()
    {
        $sourceFile = $this->getSourceFile();

        if ($sourceFile !== null) {
            $this->lox->runFile($sourceFile);
        } else {
            $this->lox->runPrompt();
        }
    }

    protected function getSourceFile()
    {
        $options = getopt('f:');

        return $options['f'] ?? null;
    }

}