<?php

namespace Uschmann\Jox\Statement;

use Uschmann\Jox\Expression\Expr;

class PrintStatement extends Stmt
{
    public function __construct(public Expr $expr)
    {
    }

    public function accept(StmtVisitor $visitor)
    {
        $visitor->visitPrintStmt($this);
    }
}