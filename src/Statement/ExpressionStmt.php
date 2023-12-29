<?php

namespace Uschmann\Jox\Statement;

use Uschmann\Jox\Expression\Expr;

class ExpressionStmt extends Stmt
{
    public function __construct(public Expr $expression)
    {
    }

    public function accept(StmtVisitor $visitor)
    {
        $visitor->visitExpressionStmt($this);
    }
}