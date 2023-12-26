<?php

namespace Uschmann\Jox\Expression;

class Literal extends Expr
{
    public function __construct(public $value)
    {
    }


    #[\Override]
    public function accept(ExprVisitor $visitor)
    {
        return $visitor->visitLiteral($this);
    }
}