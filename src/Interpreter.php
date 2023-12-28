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
    public function __construct(protected ErrorReporter $errorReporter)
    {
    }


    #[\Override] public function visitBinary(Binary $binary)
    {
        $left  = $this->evaluate($binary->left);
        $right = $this->evaluate($binary->right);

        switch($binary->operator->type) {
            case Token::TYPE_MINUS:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left - (float)$right;
            case Token::TYPE_SLASH:
                $this->checkNumberOperands($binary->operator, $left, $right);
                if($right == 0) {
                    throw new RuntimeError($binary->operator, 'Division by zero is not allowed');
                }
                return (float)$left / (float)$right;
            case Token::TYPE_STAR:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left * (float)$right;
            case Token::TYPE_PLUS:
                if(is_string($left) && is_string($right)) {
                    return $left . $right;
                }
                if(is_numeric($left) && is_numeric($right)) {
                    return $left + $right;
                }

                throw new RuntimeError($binary->operator, "Operands must be two numbers or two strings");
                break;
            case Token::TYPE_GREATER:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left > (float)$right;
            case Token::TYPE_GREATER_EQUAL:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left >= (float)$right;
            case Token::TYPE_LESS:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left < (float)$right;
            case Token::TYPE_LESS_EQUAL:
                $this->checkNumberOperands($binary->operator, $left, $right);
                return (float)$left <= (float)$right;
            case Token::TYPE_BANG_EQUAL:
                return $left != $right;
            case Token::TYPE_EQUAL_EQUAL:
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
                $this->checkNumberOperand($unary->operator, $right);
                return -(float)$right;
            case Token::TYPE_BANG:
                return !$this->isTruthy($right);
        }

        return null;
    }

    public function interpret(Expr $expr)
    {
        try {
            $result = $this->evaluate($expr);
            return $this->stringify($result);
        } catch (RuntimeError $error) {
            $this->errorReporter->runtimeError($error);
        }
    }

    protected function evaluate(Expr $expr)
    {
        return $expr->accept($this);
    }

    protected function isTruthy($value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        return true;
    }

    private function checkNumberOperand(Token $operator, $operand): void
    {
        if(is_numeric($operand)) {
            return;
        }

        throw new RuntimeError($operator, "Operand must be a number");
    }

    private function checkNumberOperands(Token $operator, $left, $right): void
    {
        if(is_numeric($left) && is_numeric($right)) {
            return;
        }

        throw new RuntimeError($operator, "Operands must be numbers");
    }

    private function stringify($value): string
    {
        if($value === null) {
            return 'nil';
        }

        return $value;
    }
}