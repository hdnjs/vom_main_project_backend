<?php
    $get_idx = $_GET['idx'];

    // 접속 정보 로드
    include $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

    $sql = "SELECT * FROM spl_products WHERE pro_idx = $get_idx";
    $result = mysqli_query($conn, $sql);   //  첫번째 파라미터: 접속정보, 두번째 파라미터: 쿼리문

    if(!mysqli_num_rows($result))    {
        echo json_encode(array("msg" => "조회된 제품이 없습니다."));
    }   else    {
        $row = mysqli_fetch_array(($result));

        echo json_encode(array('pro_idx' => $row['pro_idx'], 'pro_name' => $row['pro_name'], 'pro_pri' => $row['pro_pri'], 'pro_desc' => $row['pro_desc'], 'pro_img' => $row['pro_img'], 'pro_reg' => $row['pro_reg']));  //  첫번째 파라미터: 대상 배열, 두번째 파라미터: 배열 입력값
    }
?>