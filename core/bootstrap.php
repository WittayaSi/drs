<?php

use App\Core\App;

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
    Connection::make(App::config()['database'])
));

function view($view, $data=[])
{
    extract($data);
    return require "app/views/{$view}.view.php";
}

function json_array($json)
{
    return json_decode(json_encode($json), true);
}

function headerview()
{
    require 'app/views/layouts/head.php';
}
function footerview()
{
    require "app/views/layouts/footer.php";
}
