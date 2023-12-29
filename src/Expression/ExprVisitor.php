<?php

namespace Uschmann\Jox\Expression;

interface ExprVisitor
{
    public function visitBinary(Binary $binary);
    public function visitGrouping(Grouping $grouping);
    public function visitLiteral(Literal $literal);
    public function visitUnary(Unary $unary);
    public function visitVariable(Variable $variable);
}