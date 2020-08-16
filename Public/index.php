<?php

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

require 'vendor/autoload.php';

setlocale(LC_TIME, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$config = ["settings" => ["displayErrorDetails" => true]];

$app = new \Slim\App($config);

$app->get("[/]", function(Request $request, Response $response, array $array){

    return $response->withJson(["Success"]);

});


//Create routes for Login

require "Routes/Users.php";




$app->run();