<?php

    $host = 'localhost';
    $db = 'soaply';
    $user = 'root';
    $pass = '';

    session_start();
    $conn = new mysqli($host, $user, $pass, $db);

    if($conn->connect_errno)    {
        http_response_code(400);
        header('Content-type: text/plain');
        echo $conn->connect_errno;
        exit();
    }

?>