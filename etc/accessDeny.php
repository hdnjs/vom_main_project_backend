<?php

    session_start();

    if(!isset($_SESSION['userid']))  {
        echo json_encode(array("acs_code" => 0));
    }   else    {
        echo json_encode(array("acs_code" => 1));
    }

?>