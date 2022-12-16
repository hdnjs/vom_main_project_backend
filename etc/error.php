<?php
$screen_flag=3;   // 1스크린에 표시,2파일에 저장,3모두 표시
$reporting_flag=2;   // 1레포팅 하지 않음,2중대한 수준만 레포팅,3경미한 수준까지 모두 레포팅
//error_reporting(0);
function fatal_error_handler() {
 if (@is_array($e = @error_get_last())) {
   $code = isset($e['type']) ? $e['type'] : 0;
   $msg = isset($e['message']) ? $e['message'] : '';
   $file = isset($e['file']) ? $e['file'] : '';
   $line = isset($e['line']) ? $e['line'] : '';
   if ($code>0) customError($code,$msg,$file,$line,$context);
   }
 
}
 
/*
error_level   필수. 사용자 정의 에러를 위한 에러 리포트의 레벨을 상술합니다. 번호값 이어야만 합니다. 아래쪽이 표에 가능한 에러 리포트 레벨들이 있습니다.
error_message   필수. 사용자 정의 에러를 위한 오류 메시지를 상술합니다.
error_file   선택적. 에러가 발생한 파일 이름을 상술합니다.
error_line   선택적. 에러가 발생한 줄번호(line number)를 상술합니다.
error_context   선택적. 에러가 발생했을때 모든 변수와 그 변수의 값을 담은 배열을 상술합니다.
*/
 
function customError($level,$message,$file,$line,$context){
global $screen_flag,$reporting_flag,$errdir;
switch ($level){
 
       case E_ERROR: // 1 //
           $typestr = 'E_ERROR'; break;
       case E_WARNING: // 2 //
           $typestr = 'E_WARNING'; break;
       case E_PARSE: // 4 //
           $typestr = 'E_PARSE'; break;
       case E_NOTICE: // 8 //
           $typestr = 'E_NOTICE'; break;
       case E_CORE_ERROR: // 16 //
           $typestr = 'E_CORE_ERROR'; break;
       case E_CORE_WARNING: // 32 //
           $typestr = 'E_CORE_WARNING'; break;
       case E_COMPILE_ERROR: // 64 //
           $typestr = 'E_COMPILE_ERROR'; break;
       case E_CORE_WARNING: // 128 //
           $typestr = 'E_COMPILE_WARNING'; break;
       case E_USER_ERROR: // 256 //
           $typestr = 'E_USER_ERROR'; break;
       case E_USER_WARNING: // 512 //
           $typestr = 'E_USER_WARNING'; break;
       case E_USER_NOTICE: // 1024 //
           $typestr = 'E_USER_NOTICE'; break;
       case E_STRICT: // 2048 //
           $typestr = 'E_STRICT'; break;
       case E_RECOVERABLE_ERROR: // 4096 //
           $typestr = 'E_RECOVERABLE_ERROR'; break;
       case E_DEPRECATED: // 8192 //
           $typestr = 'E_DEPRECATED'; break;
       case E_USER_DEPRECATED: // 16384 //
           $typestr = 'E_USER_DEPRECATED'; break;
}
$err_msg="\n<b>$typestr :</b> $message <b>$file</b> on lines $line $context".date("H:i:s",time());
if($screen_flag==1 || $screen_flag==3){
    if($reporting_flag!=2 || $typestr!="E_NOTICE") // E_NOTICE 는 너무 많다
     //  echo $err_msg;
     echo json_encode(array("err_msg" => $err_msg));
}
 
if($screen_flag==2 || $screen_flag==3 || $reporting_flag==2){
      if($typestr!="E_NOTICE"){  // E_NOTICE 는 너무 많다
         $filenm=$errdir."/".date("ymd_H",time()).".txt";
       file_put_contents($filenm, $err_msg, FILE_APPEND | LOCK_EX);
    }
}
}
 
if($reporting_flag=="2" || $reporting_flag=="3") register_shutdown_function('fatal_error_handler');  // 중대한 에러수준
if($reporting_flag=="3") set_error_handler("customError");  // 경미한 에러수준
 
//mag();
//echo($agweggs);
?>
 
