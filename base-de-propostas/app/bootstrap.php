<?php
require '../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

use Illuminate\Database\Capsule\Manager as Capsule;

// use Goodby\CSV\Import\Standard\Lexer;
// use Goodby\CSV\Import\Standard\Interpreter;
// use Goodby\CSV\Import\Standard\LexerConfig;

$capsule = new Capsule;
//$app->db = $capsule;

$capsule->addConnection(array (
    'driver'    => 'mysql',
    'host'      => '127.0.01',
    'database'  => 'wp-minuta-gestao',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = new \Slim\Slim();

//$app->view(new \JsonApiView());
//$app->add(new \JsonApiMiddleware());
