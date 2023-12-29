<?php

namespace Uschmann\Jox\Expression;

use Uschmann\Jox\Token;

class Variable extends Expr
{
    public function __construct(public Token $name)
    {
    }


    #[\Override] public function accept(ExprVisitor $visitor)
    {
        return $visitor->visitVariable($this);
    }
}