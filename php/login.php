<?php

$username = $_POST["username"];
$password = $_POST["password"];

$constr = "host=127.0.0.1 port=5432 dbname=webgis user=postgres password=*******";
$con = pg_connect($constr);
if (!$con){
    echo json_encode(array(
        "success" => false,
        "message" => "Can't connect to DATABASE"
    ));
    exit(1);
}

$sql = "select count(1) from gisuser where username='{$username}' and passwd='{$password}'";
$result = pg_query($con, $sql);
if (!$result) {
    echo "query did not execute";
}
$row = pg_fetch_row($result, 0);
if (intval($row[0]) === 1) {
    echo json_encode(array("success" => true, "username" => $username));
} else {
    echo json_encode(array("success" => false));
}

pg_free_result($result);
pg_close($con);