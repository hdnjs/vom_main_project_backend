<?php

    include $_SERVER['DOCUMENT_ROOT'].'/main_backend/etc/error.php';

    include_once $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

    if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['req_sign'] == 'get_user')    {
        get_user($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'PATCH' && $_POST['req_sign'] == 'patch_user')    {
        patch_user($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q']) && $_GET['q'] == 'signout')    {
        del_user($conn);
    }

    function get_user($conn) {
        // echo json_encode(array("msg" => "사용자 데이터 요청"));

        // SELECT *(전체 조회) FROM [table name] WHERE [condition] ORDER BY [column name] DESC
        // table name의 전체 데이터를 condition 조건에 따라 조회하며, column name 기준 역순으로 나열함
        $sql = "SELECT * FROM spl_user ORDER BY user_idx DESC";
        $result = mysqli_query($conn, $sql);

        if(!mysqli_num_rows($result))   {
            echo json_encode(array("msg" => "가입된 회원이 없습니다."));
            exit();
        }   else    {
            $json_result = array(); //  빈 배열 초기화

            while($row = mysqli_fetch_array($result))   {
                array_push($json_result, array('user_idx' => $row['user_idx'], 'user_name' => $row['user_name'], 'user_id' => $row['user_id'], 'user_email' => $row['user_email'], 'user_lvl' => $row['user_lvl']));  
                //  첫번째 파라미터: 대상 배열, 두번째 파라미터: 배열 입력값
            }
        }
        
        echo json_encode($json_result);

    }

    function patch_user($conn)  {}

    function del_user($conn)  {}
?>
