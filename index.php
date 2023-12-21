<?php
require './vendor/autoload.php';

use Uschmann\Jox\App;
use Uschmann\Jox\ErrorReporter;
use Uschmann\Jox\Lox;
use Uschmann\Jox\Scanner;

$errorReporter = new ErrorReporter();
$scanner       = new Scanner($errorReporter);
$lox           = new Lox($scanner);
$app           = new App($lox);

$app->run();