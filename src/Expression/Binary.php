<?php

namespace Uschmann\Jox\Expression;

use Uschmann\Jox\Token;

class Binary extends Expr
{
    public function __construct(
        public Expr  $left,
        public Token $operator,
        public Expr  $right
    )
    {
    }

    #[\Override]
    public function accept(ExprVisitor $visitor)
    {
        return $visitor->visitBinary($this);
    }
}