<?php
require './vendor/autoload.php';

use Uschmann\Jox\App;
use Uschmann\Jox\Environment;
use Uschmann\Jox\ErrorReporter;
use Uschmann\Jox\Interpreter;
use Uschmann\Jox\Lox;
use Uschmann\Jox\Parser;
use Uschmann\Jox\Scanner;

$environment = new Environment();
$errorReporter = new ErrorReporter();
$scanner       = new Scanner($errorReporter);
$parser        = new Parser($errorReporter);
$interpreter   = new Interpreter($errorReporter, $environment);
$lox           = new Lox($scanner, $parser, $interpreter);
$app           = new App($lox);


$app->run();