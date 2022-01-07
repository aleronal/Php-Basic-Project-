<?php ob_start();

$db['db_host'] = "localhost:8889";
$db['db_user'] = "root";
$db['db_pass'] = "root";
$db['db_name'] = "cms";

foreach($db as $key => $value){

    define(strtoupper($key), $value);
}

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);



?>