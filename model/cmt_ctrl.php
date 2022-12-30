<?php

    // include $_SERVER['DOCUMENT_ROOT'].'/main_backend/etc/error.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['req_sign']) && $_GET['req_sign'] == 'post_cmt')    {
        post_cmt($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['req_sign'] == 'get_cmt')    {
        get_cmt($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'PATCH' && isset($_GET['req_sign']) && $_GET['req_sign'] == 'patch_cmt')    {
        patch_cmt($conn);
    }

    function post_cmt($conn) {
        if(isset($_SESSION['useridx'])) {
            $u_idx = $_SESSION['useridx'];
        }   else    {
            $u_idx = '';
        }
        
        $pro_idx = $_GET['p_idx'];
        $content = $_POST['cmt_cont'];
        $cmt_reg = date("Y-m-d H:i:s");

        if(!isset($_SESSION['useridx'])) {
            echo json_encode(array("msg" => "상품평을 작성하려면 로그인이 필요합니다."));
            exit();
        }

        // sql 입력 명령어 작성
        $sql = "INSERT INTO spl_cmt (cmt_u_idx, cmt_pro_idx, cmt_count, cmt_reg) VALUES (?, ?, ?, ?)";
        $stmt = $conn->stmt_init();

        if(!$stmt->prepare($sql))   {
            http_response_code(400);
            echo json_encode(array("msg" => "상품평 입력이 되지 않았습니다."));
        }

        $stmt -> bind_param("ssss", $u_idx, $pro_idx, $content, $cmt_reg);
        $stmt -> execute();

        if($stmt->affected_rows > 0)    {
            http_response_code(200);
            echo json_encode(array("msg" => "상품평이 입력되었습니다."));
        }   else    {
            http_response_code(400);
            echo json_encode(array("msg" => "상품평이 입력이 되지 않았습니다."));

        }
        // echo json_encode(array("u_idx" => $u_idx, "pro_idx" => $pro_idx, "content" => $content, "cmt_reg" => $cmt_reg));
    }

    function get_cmt($conn) {

    }

    function patch_cmt($conn)    {

    }
?>