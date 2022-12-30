<?php

    session_start();

    if(isset($_SESSION['userid']))  {
        echo json_encode(array("userid" => $_SESSION['userid'], "user_idx" => $_SESSION['useridx']));
    }   else    {
        echo json_encode(array("userid" => "guest"));
    }

?>