<?php

namespace Uschmann\Jox\Statement;

interface StmtVisitor
{

    public function visitExpressionStmt(ExpressionStmt $stmt): void;

    public function visitPrintStmt(PrintStmt $stmt): void;

    public function visitVarStmt(VarStmt $stmt): void;

}