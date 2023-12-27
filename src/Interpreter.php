<?php

namespace Uschmann\Jox;

use Uschmann\Jox\Expression\Binary;
use Uschmann\Jox\Expression\Expr;
use Uschmann\Jox\Expression\ExprVisitor;
use Uschmann\Jox\Expression\Grouping;
use Uschmann\Jox\Expression\Literal;
use Uschmann\Jox\Expression\Unary;

class Interpreter implements ExprVisitor
{

    #[\Override] public function visitBinary(Binary $binary)
    {
        $left  = $this->evaluate($binary->left);
        $right = $this->evaluate($binary->right);

        switch($binary->operator->type) {
            case Token::TYPE_MINUS:
                return (float)$left - (float)$right;
            case Token::TYPE_SLASH:
                return (float)$left / (float)$right;
            case Token::TYPE_STAR:
                return (float)$left * (float)$right;
            case Token::TYPE_PLUS:
                if(is_string($left) && is_string($right)) {
                    return $left . $right;
                }
                if(is_numeric($left) && is_numeric($right)) {
                    return $left + $right;
                }
                break;
            case Token::TYPE_GREATER:
                return (float)$left > (float)$right;
            case Token::TYPE_GREATER_EQUAL:
                return (float)$left >= (float)$right;
            case Token::TYPE_LESS:
                return (float)$left < (float)$right;
            case Token::TYPE_LESS_EQUAL:
                return (float)$left <= (float)$right;
            case Token::TYPE_BANG_EQUAL:
                return $left != $right;
            case Token::TYPE_EQUAL:
                return $left == $right;
        }
    }

    #[\Override] public function visitGrouping(Grouping $grouping)
    {
        return $this->evaluate($grouping->expression);
    }

    #[\Override] public function visitLiteral(Literal $literal)
    {
        return $literal->value;
    }

    #[\Override] public function visitUnary(Unary $unary)
    {
        $right = $this->evaluate($unary->right);

        switch ($unary->operator->type) {
            case Token::TYPE_MINUS:
                return -(float)$right;
            case Token::TYPE_BANG:
                return !$this->isTruthy($right);
        }

        return null;
    }

    protected function evaluate(Expr $expr)
    {
        return $expr->accept($this);
    }

    protected function isTruthy($value): bool
    {
        if ($value == null) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        return true;
    }
}