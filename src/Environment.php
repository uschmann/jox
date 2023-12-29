<?php

namespace Uschmann\Jox;

class Environment
{
    protected $values = [];

    public function define(string $name, $value): void
    {
        $this->values[$name] = $value;
    }

    public function get(Token $name)
    {
        if(key_exists($name->lexeme, $this->values)) {
            return $this->values[$name->lexeme];
        }

        throw new RuntimeError($name, "Undefined variable '{$name->lexeme}'");
    }
}