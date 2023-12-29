<?php

namespace Uschmann\Jox\Statement;

interface StmtVisitor
{

    public function visitExpressionStmt(Expression $stmt): void;

    public function visitPrintStmt(PrintStatement $stmt): void;

}