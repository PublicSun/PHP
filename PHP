<?php
    header( "Access-Control-Allow-Origin:*" );
    header( "Access-Control-Allow-Methods:POST,GET" );
$mysqli = new mysqli('localhost', 'root', '123456','user');
mysqli_query($mysqli,"set names utf8");
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}
$output = array();
$user_by = @$_GET['user_by'] ? $_GET['user_by'] : '';
//$password = @$_POST['password'] ? $_POST['password'] : '';
//$username = @$_POST['username'] ? $_POST['username'] : '';
//$password = @$_GET['password'] ? $_GET['password'] : '';
//$username = @$_GET['username'] ? $_GET['username'] : '';
$json = file_get_contents("php://input");
$array = json_decode($json,true);
$username=$array['username'];
$password=$array['password'];

if (empty($user_by)) {
    $output = array('data'=>NULL, 'info'=>'this is null!', 'stats'=>1);
    exit(json_encode($output));
}
if ($user_by == 'get_data') {//调用获取用户信息的接口
    //查询用户是否存在
    $sqlinfo = "select password from tb_info where username='$username'";
    $resultinfo = $mysqli->query($sqlinfo);
    $passwordinfo = $resultinfo->fetch_row();
    if ($passwordinfo[0] == $password) {
        //查询数据库
        $sql = "select * from tb_info WHERE username='$username'";
        $result = $mysqli->query($sql);
        $userInfo = $result->fetch_row();
        if ($userInfo) {//如果数据存在输出数据
            $output = array(
                //'data'=>$userInfo,
                'data' => array(array(
                    'KDA' => $userInfo[2],
                    'daye' => $userInfo[3],
                    'tuijin' => $userInfo[4],
                    'shengcun' => $userInfo[5],
                    'jingji' => $userInfo[6],
                    'shuchu' => $userInfo[7],
                )),
                'stats' => 0
            );
        } else {
            $output = array(
                'data' => array(
                    'userInfo' => $userInfo,
                ),
                'stats' => 1
            );
        }
        exit(json_encode($output));//把结果反馈给客户端
        
    }
    else{
        $output = array('data'=>NULL, 'info'=>'do not exit this user!', 'stats'=>1);
        exit(json_encode($output));
    }
}
$mysqli->close();
?>
