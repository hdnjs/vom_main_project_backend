<?php

// SELECT * FROM [table name] WHERE [option]
// 1. DB 접속
// 2. sql 쿼리 실행
// 3. 쿼리 결과 json 배열로 응답

// 접속 정보 로드
include $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

$sql = "SELECT * FROM spl_products ORDER BY pro_reg DESC LIMIT 6";
$result = mysqli_query($conn, $sql);   //  첫번째 파라미터: 접속정보, 두번째 파라미터: 쿼리문

echo json_encode(array("msg" => mysqli_fetch_array($result)));

?>