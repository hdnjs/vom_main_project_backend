<?php

    include $_SERVER['DOCUMENT_ROOT'].'/main_backend/etc/error.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/main_backend/connect/dbconn.php';

    function parse_raw_http_request(array &$a_data)
    {
      // read incoming data
      $input = file_get_contents('php://input');
    
      // grab multipart boundary from content type header
      preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
      $boundary = $matches[1];
    
      // split content by boundary and get rid of last -- element
      $a_blocks = preg_split("/-+$boundary/", $input);
      array_pop($a_blocks);

      // loop data blocks
      foreach ($a_blocks as $id => $block)
      {
        if (empty($block))
          continue;

        // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

        // parse uploaded files
        if (strpos($block, 'application/octet-stream') !== FALSE)
        {
          // match "name", then everything after "stream" (optional) except for prepending newlines 
          preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
        }
        // parse all other fields
        else
        {
          // match "name" and optional value in between newline sequences
          preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        }
        $a_data[$matches[1]] = $matches[2];
      }        
    }

    if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['req_sign'] == 'get_user')    {
        get_user($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'PATCH' && $_GET['req_sign'] == 'patch_user')    {
        patch_user($conn);
    }

    if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['req_sign'] == 'del_user')    {
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

    function patch_user($conn)  {
        $_PATCH = [];
        parse_str(file_get_contents('php://input'), $_PATCH);
        parse_raw_http_request($_PATCH);

        // $user_id = $_SESSION['userid'];
        $user_idx = $_GET['user_idx'];
        $user_lvl = $_PATCH['lvl'];

        // if(!$user_id || $user_lvl != 1) {
        //     echo    "
        //         <script>
        //         alert('잘못된 접근입니다.');
        //         location,href='/main_project/index.html';
        //         </script>
        //     ";
        //     exit();
        // }

        // 업데이트 구문: UPDATE [table name] set [update column](= [update value]) WHERE [condition]
        $sql = "UPDATE spl_user set user_lvl = ? WHERE user_idx = ?";

        $stmt = $conn->stmt_init();

        if(!$stmt->prepare($sql))   {
            http_response_code(400);
            echo json_encode(array("msg" => "레벨 수정에 실패했습니다."));
        }

        $stmt -> bind_param("ss", $user_lvl, $user_idx);
        $stmt -> execute();

        if($stmt->affected_rows > 0)    {
            // http_response_code(200);
            echo json_encode(array("msg" => "회원정보가 수정되었습니다."));
        }   else    {
            // http_response_code(400);
            echo json_encode(array("msg" => "레벨 변경이 되지 않았습니다."));

        }

        // echo json_encode(array("user_id" => $user_id, "user_idx" => $user_idx, "user_lvl" => $user_lvl));

    }

    function del_user($conn)  {

        $user_id = $_SESSION['userid'];
        $user_lvl = $_SESSION['userlvl'];
        $user_idx = $_GET['user_idx'];

        if(!$user_id || $user_lvl != 1) {
            echo    "
                <script>
                alert('잘못된 접근입니다.');
                location,href='/main_project/index.html';
                </script>
            ";
            exit();
        }


        // echo $user_idx;
        // 삭제 구문: DELETE FROM [table name] WHERE [condition]
        $sql = "DELETE FROM spl_user WHERE user_idx = $user_idx";
        mysqli_query($conn, $sql);

        echo json_encode(array("msg" => "삭제가 완료되었습니다."));
    }
?>
