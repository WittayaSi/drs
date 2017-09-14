<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';
use App\Core\Router;
use App\Core\RequestCus;

Router::load('app/routes.php')->direct( RequestCus::uri(), RequestCus::method() );
