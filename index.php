<?php
require './vendor/autoload.php';

use Uschmann\Jox\App;
use Uschmann\Jox\ErrorReporter;
use Uschmann\Jox\Lox;
use Uschmann\Jox\Parser;
use Uschmann\Jox\Scanner;

$errorReporter = new ErrorReporter();
$scanner       = new Scanner($errorReporter);
$parser        = new Parser($errorReporter);
$lox           = new Lox($scanner, $parser);
$app           = new App($lox);


$app->run();