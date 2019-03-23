<?php

$username = $_POST["username"];
$password = $_POST["password"];
$repassword = $_POST["repassword"];
$email = $_POST["eamil"];
$telephone = $_POST["telephone"];


if ($username === '') {
    echo json_encode(array("noWord" => true));
    exit(0);
}
if ($password === '') {
    echo json_encode(array("noWord" => true));
    exit(0);
}
if ($email === '') {
    echo json_encode(array("noWord" => true));
    exit(0);
}
if ($telephone === '') {
    echo json_encode(array("noWord" => true));
    exit(0);
}

$passwordpattern ="/^[a-zA-Z\d_]{6,16}$/";
if (!preg_match($passwordpattern,$password)) {
    echo json_encode(array("passFormatWrong" => true));
    exit(0);
}



$emailpattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
if (!preg_match( $emailpattern,$email) ){
    echo json_encode(array("emailFormatWrong" => true));
    exit(0);
}
$telepattern =" /^1[3|4|5|7|8]\d{9}$/";
if (!preg_match($telepattern,$telephone) ){
    echo json_encode(array("teleFormatWrong" => true));
    exit(0);
}

if ($password===$repassword) {
    
$constr = "host=127.0.0.1 port=5432 dbname=webgis user=postgres password=*******";
$con = pg_connect($constr);
if (!$con) {
    echo json_encode(array(
        "connect" => false,
        "message" => "Can't connect to DATABASE"
    ));
    exit(1);
}
$sql = "select username from gisuser where username='{$username}'";
$result = pg_query($con, $sql);

if (pg_num_rows($result)) {
    echo json_encode(array("sameUsername" => true));
    exit(0);
} else {
    $sql_insert = "insert into gisuser(username,passwd,email,telephone) values('$username','$password','$email','$telephone')";
    $res_insert = pg_query($con, $sql_insert);
    if ($res_insert) {
        echo json_encode(array("rightRegist" => true));
    } else {
        echo json_encode(array("rightRegist" => false));
    }
    pg_free_result($res_insert);
}

pg_free_result($result);
pg_close($con);

}else{
    echo json_encode(array("repasswordWrong" => true));
    exit(0);
}