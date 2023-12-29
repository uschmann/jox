<?php

namespace Uschmann\Jox\Statement;

use Uschmann\Jox\Expression\Expr;
use Uschmann\Jox\Token;

class VarStmt extends Stmt
{
    public function __construct(public Token $name, public Expr $initializer)
    {
    }

    #[\Override] public function accept(StmtVisitor $visitor)
    {
        $visitor->visitVarStmt($this);
    }
}