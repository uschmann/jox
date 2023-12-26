<?php

namespace Uschmann\Jox\Expression;

class AstPrinter implements ExprVisitor
{

    public function print(Expr $expression)
    {
        return $expression->accept($this);
    }

    #[\Override]
    public function visitBinary(Binary $binary)
    {
        return $this->paranthesize($binary->operator->lexeme, $binary->left, $binary->right);
    }

    #[\Override]
    public function visitGrouping(Grouping $grouping)
    {
        return $this->paranthesize('group', $grouping->expression);
    }

    #[\Override]
    public function visitLiteral(Literal $literal)
    {
        if($literal->value === null) {
            return 'nil';
        }

        return $literal->value;
    }

    #[\Override]
    public function visitUnary(Unary $unary)
    {
        return $this->paranthesize($unary->operator->lexeme, $unary->right);
    }

    protected function paranthesize(string $name, ...$expressions)
    {
        $str = "($name";

        foreach ($expressions as $expression) {
            $str .= ' ';
            $str .= $expression->accept($this);
        }

        $str .= ')';

        return $str;
    }
}