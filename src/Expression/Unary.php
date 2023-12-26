<?php

namespace Uschmann\Jox\Expression;

use Uschmann\Jox\Token;

class Unary extends Expr
{
    public function __construct(public Token $operator, public Expr $right)
    {
    }


    #[\Override]
    public function accept(ExprVisitor $visitor)
    {
        return $visitor->visitUnary($this);
    }
}