<?php

require('vendor/autoload.php');

$app = new Flyer\Console\Application('MyApp Testing Application', '1.0.1');

$app->useAliases();
$app->addCommand(new Flyer\Console\Commands\TestCommand);
$app->addCommand(new Flyer\Console\Commands\HelloWorldCommand);

$app->run();
