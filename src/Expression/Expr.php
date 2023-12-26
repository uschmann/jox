<?php

namespace Uschmann\Jox\Expression;

abstract class Expr
{
    public abstract function accept(ExprVisitor $visitor);
}