<?php

// SELECT * FROM [table name] WHERE [option]
// 1. DB 접속
// 2. sql 쿼리 실행
// 3. 쿼리 결과 json 배열로 응답

$limit = $_GET['qnt'];

if($limit == 'all') {
    $query_qnt = '';

}   else    {
    $query_qnt = "LIMIT $limit";
}

// 접속 정보 로드
include $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

$sql = "SELECT * FROM spl_products ORDER BY pro_reg DESC $query_qnt";

$result = mysqli_query($conn, $sql);   //  첫번째 파라미터: 접속정보, 두번째 파라미터: 쿼리문

$json_result = array(); //  빈 배열 초기화

while($row = mysqli_fetch_array($result))   {
    array_push($json_result, array('pro_idx' => $row['pro_idx'], 'pro_name' => $row['pro_name'], 'pro_pri' => $row['pro_pri'], 'pro_desc' => $row['pro_desc'], 'pro_img' => $row['pro_img'], 'pro_reg' => $row['pro_reg']));  //  첫번째 파라미터: 대상 배열, 두번째 파라미터: 배열 입력값
}

header('Cache-Control: no-cache');

echo json_encode($json_result);

?>