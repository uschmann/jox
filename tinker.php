<?php

use Uschmann\Jox\Expression\Binary;
use Uschmann\Jox\Expression\Literal;
use Uschmann\Jox\Expression\Unary;
use Uschmann\Jox\Token;

require './vendor/autoload.php';

$expression = new Binary(
    new Unary(
        new Token(Token::TYPE_MINUS, "-", null, 1),
        new Literal("100"),
    ),
    new Token(Token::TYPE_PLUS, "+", null, 1),
    new Literal("42"),
);

$astPrinter = new \Uschmann\Jox\Expression\AstPrinter();

var_dump($astPrinter->print($expression));