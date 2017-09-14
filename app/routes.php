<?php

$router->get("", "DrsController@index");
$router->post("api/getResultsOnChangedEventSection", "ApiController@getResultsOnChangedEventSection");
$router->get("api/exportExcelFile", "ApiController@exportExcelFile");
