<?php

namespace Uschmann\Jox\Statement;

abstract class Stmt
{
    public abstract function accept(StmtVisitor $visitor);
}