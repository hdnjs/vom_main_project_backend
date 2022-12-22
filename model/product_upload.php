<?php

    include $_SERVER['DOCUMENT_ROOT'].'/main_backend/etc/error.php';
    date_default_timezone_set('Asia/Seoul');

    // 1. 변수 정리: 텍스트 변수, 이미지 파일 정보 변수, 날짜 변수, 이미지 확장자 필터링 변수, 파일 사이즈 제한 변수
    // 2. 확장자 필터링, 파일 사이즈 제한 조건 로직
    // 3. 파일 업로드 로직 코드
    // 4. 접속 정보 로드
    // 5. sql 정보 입력
    // 6. 입력 완료 메시지 전달

    // 텍스트 변수
    $pro_name = $_POST['product_name'];
    $pro_price = $_POST['product_price'];
    $pro_desc = nl2br($_POST['product_desc']);  //  nl2br: 엔터로 개행 시 br 태그 자동 입력
    $pro_desc = addslashes($pro_desc);  //  따옴표 중복 작성ㅇ 시 '/' 처리를 자동으로 실행

    // 날짜 변수
    $pro_reg = date("Y-m-d H:i:s");

    // 이미지 파일 정보 변수
    $image_name = $_FILES['img_main']['name'];
    $image_tmp_name = $_FILES['img_main']['tmp_name'];
    $image_type = $_FILES['img_main']['type'];
    $image_size = $_FILES['img_main']['size'];
    $image_error = $_FILES['img_main']['error'];

    // 이미지 확장자 제한 변수
    $allowed_exp = array('jpg', 'jpeg', 'png', 'gif');
    $exp = array_pop(explode('.', $image_name));   //  '.'을 기준으로 뒷부분 문자열 반환

    // 파일 사이즈 제한 변수
    $limit = 500000;

    // 확장자 필터링
    if(!in_array($exp, $allowed_exp))   {   //  in_array: 두번째 파라미터 배열에 첫번째 파라미터 문자열이 있으면 true
        echo json_encode(array("msg" => "허용되지 않는 이미지 파일 형식입니다."));
        exit();
    }

    // 파일 사이즈 제한
    if($limit <= $image_size)   {
        echo json_encode(array("msg" => "사진 파일은 50MB를 넘을 수 없습니다."));
        exit();
    }

    // 이미지 업로드: 파일 업로드 시 파일 자체는 지정ㅇ한 디렉토리 안으로 가고, DB에는 파일 이름 문자열이 저장된다.
    if($image_name && !$image_error)    {

    }   else    {
        $image_name = "";
    }

    // echo json_encode(array("msg" => $pro_name, "price" => $pro_price, "desc" => $pro_desc, "reg" => $pro_reg, "img_name" => $image_name, "img_tmp_name" => $img_tmp_name, "img_type" => $image_type, "img_size" => $image_size, "img_error" => $image_error, "exp" => $exp));
?>