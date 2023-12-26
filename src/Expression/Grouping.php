<?php

namespace Uschmann\Jox\Expression;

class Grouping extends Expr
{
    public function __construct(public Expr $expression)
    {
    }


    #[\Override]
    public function accept(ExprVisitor $visitor)
    {
        return $visitor->visitGrouping($this);
    }
}