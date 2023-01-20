<?php

    session_start();

    if(isset($_SESSION['userid']))  {
        $userid = $_SESSION['userid'];
        $useridx = $_SESSION['useridx'];
        $userlvl = $_SESSION['userlvl'];
    }   else    {
        $userid = "guest";
        $useridx = -1;
    }

    if(isset($_SESSION['cart']))  {
        $cart_count = count($_SESSION['cart']); //  세션으로 저장된 카트 개수
    }   else    {
        $cart_count = 0;
    }

    echo json_encode(array("userid" => $userid, "user_idx" => $useridx, "cart_count" => $cart_count, "user_lvl" => $userlvl));
?>