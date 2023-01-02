<?php

    include $_SERVER['DOCUMENT_ROOT'].'/main_backend/etc/error.php';
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

    // 상품 조회
    function get_cmt($conn) {

        $p_idx = $_GET['p_idx'];
        $userid = $_SESSION['userid'];

        //  sql_cmt 테이블 전체 데이터와 spl_user 테이블의 아이디를 조회한다. (두 개의 테이블 데이터를 동시 조회하기 위해서는 테이블 간 join 필요함)
        //  조회된 데이터는 파라미터의 상품 데이터에 한정한다.
        //  조회 결과는 시간의 역순, 즉 최신순으로 나열한다.
        //  join 참조: https://pearlluck.tistory.com/46
        $sql = "SELECT spl_cmt.*, spl_user.user_id FROM spl_cmt JOIN spl_user ON spl_cmt.cmt_u_idx = spl_user.user_idx WHERE cmt_pro_idx = $p_idx ORDER BY spl_cmt.cmt_reg DESC";
        $result = mysqli_query($conn, $sql);

        if(!mysqli_num_rows($result))   {
            echo json_encode(array("msg" => "조회된 게시글이 없습니다."));
            exit();
        }   else    {
            $json_result = array(); //  빈 배열 초기화

            while($row = mysqli_fetch_array($result))   {
                array_push($json_result, array('cmt_cont' => $row['cmt_count'], 'cmt_reg' => $row['cmt_reg'], 'user_id' => $row['user_id'], "session_id" => $userid));  
                //  첫번째 파라미터: 대상 배열, 두번째 파라미터: 배열 입력값
            }
        }
        
        echo json_encode($json_result);
        // echo json_encode(array("p_idx" => $p_idx, "userid" => $userid));
    }

    function patch_cmt($conn)    {

    }
?>