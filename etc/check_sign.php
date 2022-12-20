<?php

    session_start();

    if(isset($_SESSION['userid']))  {
        echo json_encode(array("userid" => $_SESSION['userid']));
    }   else    {
        echo json_encode(array("userid" => "guest"));
    }

?>